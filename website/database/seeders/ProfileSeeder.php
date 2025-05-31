<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
                'alamat' => 'Jl. Contoh Alamat ' . $user->id,
                'telepon' => '08' . rand(1000000000, 9999999999),
                'tanggal_lahir' => now()->subYears(rand(20, 35))->subDays(rand(0, 365)),
                'foto' => 'images/profiles/default.png',
            ]);
        }
    }
}
