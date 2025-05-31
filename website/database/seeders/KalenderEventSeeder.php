<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KalenderEvent;

class KalenderEventSeeder extends Seeder
{
    public function run(): void
    {
        KalenderEvent::factory()->count(10)->create();
    }
}
