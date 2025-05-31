<?php

namespace Database\Factories;

use App\Models\KalenderEvent;
use App\Models\Cuti;
use Illuminate\Database\Eloquent\Factories\Factory;

class KalenderEventFactory extends Factory
{
    protected $model = KalenderEvent::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('now', '+1 month');
        $end = (clone $start)->modify('+' . rand(1, 3) . ' days');

        return [
            'title' => $this->faker->sentence(3),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'cuti_id' => Cuti::inRandomOrder()->first()?->id ?? Cuti::factory()->create()->id,
        ];
    }
}
