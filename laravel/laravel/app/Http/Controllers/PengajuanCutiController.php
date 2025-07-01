<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use App\Models\SuratCutiResmi;
use App\Models\Users;
use App\Models\JenisCuti;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Carbon;
// FIX: 'QrCode' facade dihapus karena tidak lagi digunakan
// use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class PengajuanCutiController extends Controller
{
    public function index()
    {
        $pengajuanAktif = PengajuanCuti::with(['user:id,name', 'jenisCuti:id,nama', 'suratCutiResmi'])
            ->whereHas('suratCutiResmi', function ($query) {
                $query->where('status', '!=', 'kadaluwarsa');
            })
            ->latest()->get();
        
        $pengajuanByStatus = [
            'diajukan'  => $pengajuanAktif->filter(fn($p) => $p->suratCutiResmi?->status === 'diajukan'),
            'disetujui' => $pengajuanAktif->filter(fn($p) => $p->suratCutiResmi?->status === 'disetujui'),
            'ditolak'   => $pengajuanAktif->filter(fn($p) => $p->suratCutiResmi?->status === 'ditolak'),
        ];
        
        $karyawanList = Users::where('role', 'karyawan')->orderBy('name')->get();
        $jenisCutiList = JenisCuti::get();

        return view('manajemen-cuti', [ // Pastikan nama view ini benar
            'pengajuanByStatus' => $pengajuanByStatus,
            'karyawanList'      => $karyawanList,
            'jenisCutiList'     => $jenisCutiList,
        ]);
    }

    /**
     * Menyimpan pengajuan baru, membuat surat draf, dan mencatat log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'sometimes|required_if:auth()->user()->role,admin|exists:users,id',
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'        => 'required|string|max:1000',
            'tanda_tangan'  => 'required|string',
            'lampiran'      => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:2048',
        ]);

        try {
            $pengajuan = DB::transaction(function () use ($validated, $request) {
                $userId = auth()->user()->role === 'admin' ? $request->user_id : auth()->id();
                $targetUser = Users::with('profile')->findOrFail($userId);
                
                if ($targetUser->role === 'karyawan' && (!$targetUser->profile || $targetUser->profile->sisa_kuota_cuti <= 0)) {
                    throw new \Exception('Kuota cuti Anda sudah habis.');
                }
                
                $dataToCreate = $validated;
                $dataToCreate['user_id'] = $userId;
                unset($dataToCreate['tanda_tangan'], $dataToCreate['lampiran']);

                $signatureImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $validated['tanda_tangan']));
                $signaturePath = 'tanda-tangan-cuti/' . 'ttd_' . $targetUser->id . '_' . time() . '.png';
                Storage::disk('public')->put($signaturePath, $signatureImage);
                $dataToCreate['tanda_tangan_path'] = $signaturePath;

                if ($request->hasFile('lampiran')) {
                    $lampiranPath = $request->file('lampiran')->store('lampiran-cuti', 'public');
                    $dataToCreate['lampiran_path'] = $lampiranPath;
                }

                $pengajuan = PengajuanCuti::create($dataToCreate);
                
                $jenisCuti = JenisCuti::with('templates')->findOrFail($validated['jenis_cuti_id']);
                if (!$jenisCuti->templates?->file_path || !Storage::disk('public')->exists($jenisCuti->templates->file_path)) {
                    throw new \Exception('Template surat tidak ditemukan.');
                }
                
                $nomorSurat = 'HRD/' . date('Y/m') . '/' . $pengajuan->id;
                $templatePath = storage_path('app/public/' . $jenisCuti->templates->file_path);
                $templateProcessor = new TemplateProcessor($templatePath);
                
                // Mengisi template
                $templateProcessor->setValue('nama_lengkap', $targetUser->profile->nama_lengkap ?? $targetUser->name);
                $templateProcessor->setValue('jabatan', $targetUser->profile->jabatan ?? '-');
                $templateProcessor->setValue('nomor_surat', $nomorSurat);
                $templateProcessor->setValue('tanggal_pengajuan', now()->format('d F Y'));
                $templateProcessor->setValue('tanggal_mulai', Carbon::parse($validated['tanggal_mulai'])->format('d F Y'));
                $templateProcessor->setValue('tanggal_selesai', Carbon::parse($validated['tanggal_selesai'])->format('d F Y'));
                $templateProcessor->setValue('alasan', $validated['alasan']);
                $templateProcessor->setImageValue('tanda_tangan', ['path' => storage_path('app/public/' . $signaturePath), 'width' => 120, 'height' => 60]);
                
                // FIX: Menghapus semua logika yang berhubungan dengan QR Code
                
                $newFileName = 'surat-draf-' . str_replace('/', '-', $nomorSurat) . '.docx';
                $destinationPath = 'surat-cuti-diajukan';
                $newFilePath = $destinationPath . '/' . $newFileName;
                
                if (!Storage::disk('public')->exists($destinationPath)) {
                    Storage::disk('public')->makeDirectory($destinationPath);
                }
                $templateProcessor->saveAs(storage_path('app/public/' . $newFilePath));
                
                SuratCutiResmi::create([
                    'pengajuan_cuti_id' => $pengajuan->id,
                    'status'            => 'diajukan',
                    'nomor_surat'       => $nomorSurat,
                    'file_hasil_path'   => $newFilePath,
                    'tanda_tangan_path' => $signaturePath,
                ]);

                if ($targetUser->role === 'karyawan') {
                    $targetUser->profile->decrement('sisa_kuota_cuti');
                }

                return $pengajuan;
            });

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'membuat_pengajuan_cuti',
                'description' => 'Pengguna ' . Auth::user()->name . ' telah membuat pengajuan cuti.',
                'loggable_id' => $pengajuan->id,
                'loggable_type' => get_class($pengajuan),
            ]);

            return redirect()->route('manajemen-cuti')->with('success', 'Pengajuan cuti dan draf surat berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error('GAGAL MEMBUAT PENGAJUAN CUTI: ' . $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return back()->with('error', 'Terjadi kesalahan internal. Silakan hubungi administrator.');
        }
    }

    /**
     * Menampilkan detail satu pengajuan (untuk dipanggil oleh JavaScript).
     */
    public function show(PengajuanCuti $pengajuanCuti)
    {
        $pengajuanCuti->load(['user.profile', 'jenisCuti', 'suratCutiResmi.approver']);
        return response()->json($pengajuanCuti);
    }

    /**
     * Memproses persetujuan atau penolakan oleh Admin.
     */
    public function update(Request $request, PengajuanCuti $pengajuanCuti)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki hak akses.');
        }

        $validated = $request->validate(['status' => 'required|in:disetujui,ditolak', 'catatan_approval' => 'nullable|string']);

        try {
            $suratResmi = $pengajuanCuti->suratCutiResmi;
            if (!$suratResmi || $suratResmi->status !== 'diajukan') {
                throw new \Exception('Pengajuan ini sudah pernah diproses.');
            }
            
            $suratResmi->update([
                'status'            => $validated['status'],
                'approved_by'       => Auth::id(),
                'approved_at'       => now(),
                'catatan_approval'  => $validated['catatan_approval'],
            ]);

            return redirect()->route('manajemen-cuti')->with('success', 'Pengajuan telah diproses.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pengajuan cuti.
     */
    public function destroy(PengajuanCuti $pengajuanCuti)
    {
        $pengajuanCuti->delete();
        return redirect()->route('manajemen-cuti')->with('success', 'Pengajuan berhasil dihapus.');
    }
    
    // FIX: Method verifySignature dihapus karena tidak lagi diperlukan
}
