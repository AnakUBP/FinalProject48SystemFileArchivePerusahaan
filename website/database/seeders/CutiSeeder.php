<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuti;
use Carbon\Carbon;

class CutiSeeder extends Seeder
{
    public function run(): void
    {
        Cuti::create([
            'user_id' => 2, // asumsikan karyawan ID 2
            'jenis_cuti_id' => 1, // Cuti Tahunan
            'tanggal_mulai' => Carbon::now()->addDays(3)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(5)->toDateString(),
            'alasan' => 'Liburan keluarga',
            'status' => 'diajukan',
        ]);

        Cuti::create([
            'user_id' => 2,
            'jenis_cuti_id' => 2, // Cuti Sakit
            'tanggal_mulai' => Carbon::now()->subDays(7)->toDateString(),
            'tanggal_selesai' => Carbon::now()->subDays(5)->toDateString(),
            'alasan' => 'Sakit demam',
            'status' => 'disetujui',
        ]);
    }
}
