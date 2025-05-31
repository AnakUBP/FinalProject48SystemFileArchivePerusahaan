<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArsipCutiSeeder extends Seeder
{
    public function run(): void
    {
        // Cari cuti dengan nomor surat yang diawali 'Admin'
        $cuti = DB::table('cuti')->where('nomor_surat', 'Admin/2025/001')->first();

        if ($cuti) {
            DB::table('arsip_cuti')->insert([
                'cuti_id' => $cuti->id,
                'nomor_surat' => $cuti->nomor_surat,
                'file_surat' => 'arsip/surat_cuti_2025_001.pdf' // sesuai migrasi
            ]);
        }
    }
}
