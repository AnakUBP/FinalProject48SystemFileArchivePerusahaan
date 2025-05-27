<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;

class DashboardController extends Controller
{
    public function summary()
    {
        // Jumlah surat masuk dan keluar berdasarkan status
        $suratMasuk = Cuti::where('status', 'masuk')->count();
        $suratKeluar = Cuti::where('status', 'selesai')->count();

        return response()->json([
            'surat_masuk' => $suratMasuk,
            'surat_keluar' => $suratKeluar
        ]);
    }
}
