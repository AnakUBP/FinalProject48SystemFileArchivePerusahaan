<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ],
            [
                'name' => 'HRD',
                'email' => 'hrd@example.com',
                'password' => Hash::make('password'),
                'role' => 'hrd'
            ],
            [
                'name' => 'Karyawan 1',
                'email' => 'karyawan1@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan'
            ]
        ]);
    }
}
