<?php

namespace Database\Factories;

use App\Models\Cuti;
use App\Models\User;
use App\Models\JenisCuti;
use Illuminate\Database\Eloquent\Factories\Factory;

class CutiFactory extends Factory
{
    protected $model = Cuti::class;

    public function definition(): array
    {
        $mulai = $this->faker->dateTimeBetween('+1 days', '+5 days');
        $selesai = (clone $mulai)->modify('+3 days');

        return [
            'user_id' => User::factory(),
            'jenis_cuti_id' => JenisCuti::factory(),
            'tanggal_mulai' => $mulai->format('Y-m-d'),
            'tanggal_selesai' => $selesai->format('Y-m-d'),
            'alasan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['diajukan','diproses','disetujui','ditolak']),
            'nomor_surat' => 'HRD/2025/' . $this->faker->unique()->numerify('###'),
            'file_surat' => null,
        ];
    }
}
