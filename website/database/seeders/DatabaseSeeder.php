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

        // Panggil seeder lainnya dengan urutan benar
        $this->call([
            TemplateSeeder::class,    // harus pertama agar tabel templates sudah terisi
            JenisCutiSeeder::class,   // setelah templates
            ProfileSeeder::class,
            CutiSeeder::class,
            ApprovalLogSeeder::class,
            ArsipCutiSeeder::class,
            LogSuratSeeder::class,
            KalenderEventSeeder::class,
        ]);
    }
}
