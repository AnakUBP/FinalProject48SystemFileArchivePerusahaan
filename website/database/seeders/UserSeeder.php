<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Karyawan user
        User::create([
            'name' => 'Karyawan Satu',
            'email' => 'karyawan1@example.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
        ]);

        User::create([
            'name' => 'Karyawan Dua',
            'email' => 'karyawan2@example.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
        ]);
    }
}
