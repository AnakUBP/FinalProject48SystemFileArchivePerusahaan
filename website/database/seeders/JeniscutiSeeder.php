<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisCuti;

class JenisCutiSeeder extends Seeder
{
    public function run(): void
    {
        $cuti = ['Cuti Tahunan', 'Cuti Sakit', 'Cuti Melahirkan', 'Cuti Khusus'];

        foreach ($cuti as $jenis) {
            JenisCuti::create([
                'nama' => $jenis,
            ]);
        }
    }
}
