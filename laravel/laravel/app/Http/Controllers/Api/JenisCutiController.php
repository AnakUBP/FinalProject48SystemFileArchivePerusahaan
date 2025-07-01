<?php
// =========================================================
// File: app/Http/Controllers/Api/JenisCutiController.php
// =========================================================
// Ini adalah isi dari controller baru Anda.

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use Illuminate\Http\Request;

class JenisCutiController extends Controller
{
    /**
     * Mengembalikan daftar semua jenis cuti yang aktif.
     */
    public function index()
    {
        try {
            // Asumsi model JenisCuti memiliki kolom 'is_active'
            $jenisCuti = JenisCuti::all();

            return response()->json($jenisCuti, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jenis cuti.'
            ], 500);
        }
    }
}
