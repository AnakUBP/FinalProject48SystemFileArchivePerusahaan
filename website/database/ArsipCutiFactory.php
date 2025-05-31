<?php

namespace Database\Factories;

use App\Models\ArsipCuti;
use App\Models\Cuti;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArsipCutiFactory extends Factory
{
    protected $model = ArsipCuti::class;

    public function definition(): array
    {
        return [
            'cuti_id' => Cuti::factory(),
            'nomor_surat' => 'HRD/2025/' . $this->faker->unique()->numerify('###'),
            'file_path' => 'arsip/' . $this->faker->uuid() . '.pdf',
            'tanggal_arsip' => now(),
        ];
    }
}
