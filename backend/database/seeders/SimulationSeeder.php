<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', 'alumno@demo.com')->value('id');
        if (!$userId) return;

        $rows = [
            [
                'user_id' => $userId,
                'investment_type' => 'plazo_fijo',
                'amount' => 100000,
                'simulated_return' => 112000,
            ],
            [
                'user_id' => $userId,
                'investment_type' => 'bono_soberano',
                'amount' => 150000,
                'simulated_return' => 168000,
            ],
        ];

        foreach ($rows as $r) {
            DB::table('simulations')->insert($r + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
