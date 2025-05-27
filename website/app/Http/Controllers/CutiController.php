<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CutiController extends Controller
{
    // Menampilkan semua pengajuan cuti (khusus admin)
    public function index()
    {
        return response()->json(Cuti::with('jenisCuti', 'user')->get());
    }

    // Pengajuan cuti oleh karyawan
    public function store(Request $request)
    {
        $request->validate([
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_pengajuan' => 'required|date',
            'keperluan' => 'required|string|max:255',
        ]);

        // Buat nomor surat otomatis
        $nomorSurat = 'HRD/' . now()->format('Y') . '/' . strtoupper(Str::random(4));

        $cuti = Cuti::create([
            'user_id' => Auth::id(),
            'jenis_cuti_id' => $request->jenis_cuti_id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'keperluan' => $request->keperluan,
            'nomor_surat' => $nomorSurat,
            'status' => 'pending'
        ]);

        return response()->json($cuti, 201);
    }

    // Riwayat cuti milik user yang login
    public function myCuti()
    {
        return response()->json(Cuti::with('jenisCuti')->where('user_id', Auth::id())->get());
    }

    // Admin menyetujui pengajuan cuti
    public function approve($id)
    {
        $cuti = Cuti::findOrFail($id);

        if ($cuti->status !== 'pending') {
            return response()->json(['message' => 'Pengajuan cuti sudah diproses sebelumnya.'], 400);
        }

        $cuti->status = 'disetujui';
        $cuti->save();

        ApprovalLog::create([
            'cuti_id' => $cuti->id,
            'approved_by' => Auth::id(),
            'status' => 'disetujui',
            'catatan' => 'Disetujui oleh admin',
            'tanggal' => now(),
        ]);

        return response()->json([
            'message' => 'Pengajuan cuti disetujui.',
            'data' => $cuti,
        ]);
    }

    // Admin menolak pengajuan cuti
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:255',
        ]);

        $cuti = Cuti::findOrFail($id);

        if ($cuti->status !== 'pending') {
            return response()->json(['message' => 'Pengajuan cuti sudah diproses sebelumnya.'], 400);
        }

        $cuti->status = 'ditolak';
        $cuti->save();

        ApprovalLog::create([
            'cuti_id' => $cuti->id,
            'approved_by' => Auth::id(),
            'status' => 'ditolak',
            'catatan' => $request->input('catatan') ?? 'Ditolak oleh admin',
            'tanggal' => now(),
        ]);

        return response()->json([
            'message' => 'Pengajuan cuti ditolak.',
            'data' => $cuti,
        ]);
    }
}
