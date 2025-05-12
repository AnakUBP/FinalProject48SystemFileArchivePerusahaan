<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 10 user dummy
        User::factory(10)->create();

        // Panggil seeder lainnya
        $this->call([
            ProfileSeeder::class,
            // Tambahkan seeder lain di sini jika ada
        ]);
    }
}
