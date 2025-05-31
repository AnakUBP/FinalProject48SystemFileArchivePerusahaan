<?php

namespace Database\Factories;

use App\Models\LogSurat;
use App\Models\Cuti;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogSuratFactory extends Factory
{
    protected $model = LogSurat::class;

    public function definition()
    {
        return [
            'jenis' => $this->faker->randomElement(['masuk', 'keluar']),
            'cuti_id' => Cuti::inRandomOrder()->first()?->id ?? Cuti::factory()->create()->id,
            'nomor_surat' => 'Admin/2025/' . $this->faker->unique()->numerify('LOG###'),
            'kategori' => $this->faker->word(),
            'status' => $this->faker->randomElement(['baru', 'diproses', 'selesai']),
            'catatan' => $this->faker->sentence(),
            'waktu' => now(),
        ];
    }
}
