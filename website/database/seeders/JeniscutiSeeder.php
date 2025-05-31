<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisCutiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil id template yang sudah ada
        $template = DB::table('templates')->first();

        if (!$template) {
            echo "Seeder JenisCuti dilewati karena tidak ada data di tabel templates.\n";
            return;
        }

        DB::table('jenis_cuti')->insert([
            [
                'nama_cuti' => 'Cuti Tahunan',
                'kode_cuti' => 'CT-01',
                'keterangan' => 'Cuti tahunan biasa',
                'templates_id' => $template->id,
            ],
            [
                'nama_cuti' => 'Cuti Sakit',
                'kode_cuti' => 'CS-02',
                'keterangan' => 'Cuti karena sakit',
                'templates_id' => $template->id,
            ],
        ]);
    }
}
