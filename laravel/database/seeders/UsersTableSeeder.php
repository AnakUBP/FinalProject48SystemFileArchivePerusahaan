<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'HRD',
                'email' => 'hrd@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin', // ubah ke 'hrd' jika diperlukan
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Karyawan 1',
                'email' => 'karyawan1@example.com',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
