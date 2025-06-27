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
                'email' => 'fajariman2004@gmail.com',
                'password' => Hash::make('tdaghdavsbdhihaj5678965678'),
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'fajar',
                'email' => 'if23.fajarpratama@mhs.ubpkarawang.ac.id',
                'password' => Hash::make('mahasiswayangtakabadi625'),
                'role' => 'karyawan',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
