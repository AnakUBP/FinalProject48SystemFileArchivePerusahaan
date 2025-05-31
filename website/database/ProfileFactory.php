<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-18 years'),
            'foto' => 'default.jpg', // atau bisa random url/image
        ];
    }
}
