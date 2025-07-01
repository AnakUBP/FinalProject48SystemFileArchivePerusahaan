<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatSuratController extends Controller
{
    /**
     * Menampilkan halaman riwayat surat.
     */
    public function index()
    {
        $user = Auth::user();
        $query = PengajuanCuti::with(['user:id,name', 'jenisCuti:id,nama', 'suratCutiResmi']);

        // Jika yang login adalah karyawan, hanya tampilkan riwayat miliknya.
        if ($user->role === 'karyawan') {
            $query->where('user_id', $user->id);
        }

        // Ambil semua data (termasuk yang kadaluwarsa) dan urutkan dari yang terbaru.
        $riwayatSurat = $query->latest()->paginate(15); // Menggunakan paginate untuk data yang banyak

        return view('riwayat', compact('riwayatSurat'));
    }
        public function show(PengajuanCuti $pengajuanCuti)
    {
        // Otorisasi: Pastikan pengguna hanya bisa melihat detail miliknya, kecuali admin.
        if (auth()->user()->role !== 'admin' && auth()->id() !== $pengajuanCuti->user_id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }
        
        // Eager load semua relasi yang dibutuhkan untuk ditampilkan di modal
        $pengajuanCuti->load(['user.profile', 'jenisCuti', 'suratCutiResmi.approver']);
        
        return response()->json($pengajuanCuti);
    }
}
