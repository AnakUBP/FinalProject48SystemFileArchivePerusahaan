<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogSurat;

class LogSuratSeeder extends Seeder
{
    public function run(): void
    {
        LogSurat::factory()->count(10)->create();
    }
}
