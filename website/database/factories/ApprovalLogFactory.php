<?php

namespace Database\Factories;

use App\Models\ApprovalLog;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalLogFactory extends Factory
{
    protected $model = ApprovalLog::class;

    public function definition(): array
    {
        return [
            'cuti_id' => Cuti::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['disetujui','ditolak']),
            'catatan' => $this->faker->sentence(),
            'tanggal_approval' => now(),
        ];
    }
}
