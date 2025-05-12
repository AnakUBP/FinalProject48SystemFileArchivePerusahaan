<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalLogSeeder extends Seeder
{
    public function run(): void
    {
        $cuti = DB::table('cuti')->first();
        $hrd = DB::table('users')->where('role', 'hrd')->first();

        if ($cuti && $hrd) {
            DB::table('approval_logs')->insert([
                'cuti_id' => $cuti->id,
                'user_id' => $hrd->id,
                'status' => 'disetujui',
                'catatan' => 'Cuti disetujui oleh HRD'
            ]);
        }
    }
}
