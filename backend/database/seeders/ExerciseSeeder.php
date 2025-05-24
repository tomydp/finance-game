<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          Exercise::create([
            'lesson_id' => 1,
            'type' => 'multiple_choice',
            'question' => '¿Cuál de estos es un ingreso?',
            'options' => json_encode(['Salario', 'Alquiler', 'Deuda', 'Intereses a pagar']),
            'correct_answer' => 'Salario',
        ]);

        Exercise::create([
            'lesson_id' => 2,
            'type' => 'multiple_choice',
            'question' => '¿Qué es un gasto fijo?',
            'options' => json_encode(['Netflix', 'Impuestos', 'Café', 'Cine']),
            'correct_answer' => 'Impuestos',
        ]);
    }
}
