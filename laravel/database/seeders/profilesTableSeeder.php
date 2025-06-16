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
                'tanggal_lahir' => '1990-01-01',
                'jabatan' => 'Administrator',
                'jenis_kelamin' => 'pria',
                'foto' => null,
            ],
            [
                'users_id' => 2,
                'nama_lengkap' => 'Hana Rahma Dewi',
                'alamat' => 'Jl. Mawar No. 12',
                'telepon' => '082112223334',
                'tanggal_lahir' => '1988-06-15',
                'jabatan' => 'HRD',
                'jenis_kelamin' => 'wanita',
                'foto' => null,
            ],
            [
                'users_id' => 3,
                'nama_lengkap' => 'Budi Santoso',
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
