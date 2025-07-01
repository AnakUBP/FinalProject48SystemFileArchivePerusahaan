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
                'email' => 'admin@contoh.com',
                'password' => Hash::make('password_valid'),
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'fajar',
                'email' => 'karyawan@contoh.com ',
                'password' => Hash::make('password_valid'),
                'role' => 'karyawan',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
