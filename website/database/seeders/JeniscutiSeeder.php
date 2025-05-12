<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisCutiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_cuti')->insert([
            ['nama_cuti' => 'Cuti Tahunan', 'kode_cuti' => 'CT-01', 'keterangan' => 'Cuti tahunan biasa'],
            ['nama_cuti' => 'Cuti Sakit', 'kode_cuti' => 'CS-02', 'keterangan' => 'Cuti karena sakit'],
        ]);
    }
}
