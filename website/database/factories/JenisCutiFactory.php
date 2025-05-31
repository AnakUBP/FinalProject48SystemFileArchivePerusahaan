<?php

namespace Database\Factories;

use App\Models\JenisCuti;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisCutiFactory extends Factory
{
    protected $model = JenisCuti::class;

    public function definition(): array
    {
        return [
            'nama_cuti' => $this->faker->word(),
            'kode_cuti' => strtoupper($this->faker->unique()->bothify('??-###')),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
