<?php

namespace Database\Seeders;
use App\Models\Simulation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Simulation::create([
            'user_id' => 1,
            'investment_type' => 'Plazo fijo',
            'amount' => 10000,
            'simulated_return' => 10400,
        ]);

        Simulation::create([
            'user_id' => 1,
            'investment_type' => 'Acciones',
            'amount' => 15000,
            'simulated_return' => 17000,
        ]);
    }
}
