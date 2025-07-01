<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfilesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber(),
            'tanggal_lahir' => fake()->date(),
            'jabatan' => fake()->jobTitle(),
            'jenis_kelamin' => fake()->randomElement(['pria', 'wanita', 'lainnya']),
            'foto_profil' => 'profiles/default.png', // Contoh path default
        ];
    }
}
