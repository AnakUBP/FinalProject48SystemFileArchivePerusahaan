<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratCutiResmi;
use App\Models\PengajuanCuti;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data statistik dan detail cuti.
     */
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // ... (Logika suratMasuk dan suratKeluar Anda tetap sama) ...
        $suratMasuk = SuratCutiResmi::where('status', 'diajukan')->whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();
        $suratKeluar = SuratCutiResmi::where('status', 'disetujui')->whereYear('approved_at', $currentYear)->whereMonth('approved_at', $currentMonth)->count();

        // FIX: Mengambil detail karyawan yang lebih lengkap (termasuk foto)
        $detailCutiPerTanggal = [];
        $pengajuanDisetujui = PengajuanCuti::with('user.profile') // Eager load user dan profilnya
            ->whereHas('suratCutiResmi', function ($query) {
                $query->where('status', 'disetujui');
            })->get();

        foreach ($pengajuanDisetujui as $pengajuan) {
            $period = CarbonPeriod::create($pengajuan->tanggal_mulai, $pengajuan->tanggal_selesai);
            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                if (!isset($detailCutiPerTanggal[$dateString])) {
                    $detailCutiPerTanggal[$dateString] = [];
                }
                // Simpan objek yang berisi nama dan path foto
                $detailCutiPerTanggal[$dateString][] = [
                    'name' => $pengajuan->user->name,
                    'foto' => $pengajuan->user->profile?->foto 
                ];
            }
        }
        
        return view('dashboard', compact('suratMasuk', 'suratKeluar', 'detailCutiPerTanggal'));
    }
}
