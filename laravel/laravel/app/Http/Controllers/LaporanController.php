<?php

namespace App\Http\Controllers;

use App\Models\SuratCutiResmi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menyiapkan data dan menampilkan halaman laporan dinamis.
     */
    public function index(Request $request)
    {
        // Tentukan jenis tampilan: 'bulanan' atau 'tahunan'
        $viewType = $request->input('view_type', 'bulanan');

        $selectedYear = $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month', Carbon::now()->month);

        $reportData = [];
        $historiSurat = collect();

        if ($viewType === 'bulanan') {
            // === DATA UNTUK LAPORAN BULANAN ===
            
            // 1. Statistik Harian untuk Diagram Batang
            $reportData = SuratCutiResmi::select(
                    DB::raw("DAY(created_at) as hari"),
                    DB::raw('count(id) as total')
                )
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->groupBy('hari')
                ->orderBy('hari', 'asc')
                ->get();

            // 2. Histori Pengajuan Surat untuk Tabel
            // FIX: Eager load relasi yang lebih lengkap untuk tabel detail
            $historiSurat = SuratCutiResmi::with(['pengajuanCuti.user:id,name', 'pengajuanCuti.jenisCuti:id,nama'])
                ->whereNotNull('nomor_surat')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->latest('approved_at')
                ->paginate(15);

        } elseif ($viewType === 'tahunan') {
            // === DATA UNTUK LAPORAN TAHUNAN ===

            // 1. Statistik Bulanan untuk Diagram Batang
            $reportData = SuratCutiResmi::select(
                    DB::raw("MONTH(created_at) as bulan"),
                    DB::raw("count(id) as total")
                )
                ->whereYear('created_at', $selectedYear)
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->get();
        }

        // Kirim semua data yang dibutuhkan ke view
        return view('laporan', compact(
            'viewType',
            'reportData',
            'historiSurat',
            'selectedYear',
            'selectedMonth'
        ));
    }
}
