<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use App\Models\SuratCutiResmi;
use App\Models\JenisCuti;
use App\Models\ActivityLog;
use App\Models\Users; // Pastikan model Users di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor; // Import PHPWord
use Illuminate\Support\Carbon; // Import Carbon

class CutiController extends Controller
{
    /**
     * Mengembalikan daftar pengajuan cuti milik pengguna yang sedang login.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            $pengajuan = PengajuanCuti::with(['jenisCuti', 'suratCutiResmi'])
                ->where('user_id', $user->id)
                ->whereHas('suratCutiResmi', function ($query) {
                    $query->where('status', '!=', 'kadaluwarsa');
                })
                ->latest()
                ->get();
                
            return response()->json($pengajuan, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memuat data cuti.'], 500);
        }
    }

    /**
     * Menyimpan pengajuan baru DAN LANGSUNG membuat file surat draf.
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


            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cuti dan draf surat berhasil dibuat.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show(Request $request, PengajuanCuti $pengajuanCuti)
    {
        // Pastikan pengguna hanya bisa melihat detail cutinya sendiri
        if ($request->user()->id !== $pengajuanCuti->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eager load semua relasi yang dibutuhkan
        $pengajuanCuti->load(['user.profile', 'jenisCuti', 'suratCutiResmi.approver']);

        return response()->json($pengajuanCuti, 200);
    }
}
