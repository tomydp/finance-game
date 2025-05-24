<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Achievement::create([
            'name' => 'Primer curso completado',
            'description' => 'Completaste tu primer curso en la plataforma.',
        ]);

        Achievement::create([
            'name' => '10 respuestas correctas',
            'description' => 'Respondiste correctamente a 10 ejercicios.',
        ]);
    }
}
