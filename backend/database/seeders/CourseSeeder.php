<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Fundamentos Financieros',
                'description' => 'Presupuesto, ahorro, objetivos y manejo básico del dinero.',
                'difficulty' => 'facil',
            ],
            [
                'name' => 'Introducción a Inversiones',
                'description' => 'Riesgo vs retorno, plazo fijo, bonos, acciones y fondos.',
                'difficulty' => 'medio',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 1',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'dificil',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 2',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'dificil',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 3',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'medio',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 4',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'facil',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 5',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'dificil',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 6',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'medio',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 7',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'facil',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 8',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'medio',
            ],
            [
                'name' => 'Crédito y Deuda Responsable 9',
                'description' => 'Score crediticio, interés compuesto y cómo evitar sobreendeudarte.',
                'difficulty' => 'dificil',
            ]
        ];

        foreach ($courses as $c) {
            DB::table('courses')->updateOrInsert(
                ['name' => $c['name']],
                $c + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}