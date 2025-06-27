<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfilesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profiles')->insert([
            [
                'users_id' => 1, // pastikan ID sesuai dengan user yang ada
                'nama_lengkap' => 'Admin Utama',
                'alamat' => 'Jl. Merdeka No. 1',
                'telepon' => '081234567890',
                'tanggal_lahir' => '2004-01-01',
                'jabatan' => 'Administrator',
                'jenis_kelamin' => 'pria',
                'foto' => null,
            ],
            [
                'users_id' => 2,
                'nama_lengkap' => 'fajar wisnu pratama',
                'alamat' => 'Jl. Kenanga No. 5',
                'telepon' => '085677889900',
                'tanggal_lahir' => '1995-03-10',
                'jabatan' => 'Staff',
                'jenis_kelamin' => 'pria',
                'foto' => null,
            ]
        ]);
    }
}
