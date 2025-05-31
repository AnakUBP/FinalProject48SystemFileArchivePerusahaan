<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CutiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cuti')->insert([
            [
                'user_id' => 3,
                'jenis_cuti_id' => 1,
                'tanggal_mulai' => now()->addDays(5)->toDateString(),
                'tanggal_selesai' => now()->addDays(10)->toDateString(),
                'alasan' => 'Liburan keluarga',
                'status' => 'diajukan',
                'nomor_surat' => 'Admin/2025/001'
            ],
            [
                'user_id' => 3,
                'jenis_cuti_id' => 2,
                'tanggal_mulai' => now()->addDays(2)->toDateString(),
                'tanggal_selesai' => now()->addDays(3)->toDateString(),
                'alasan' => 'Cuti karena demam',
                'status' => 'diajukan',
                'nomor_surat' => 'Admin/2025/002'
            ]
        ]);
    }
}
