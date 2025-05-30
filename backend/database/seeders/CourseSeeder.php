<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'name' => 'Finanzas Personales',
            'description' => 'Aprendé a manejar tu dinero de forma eficiente.',
            'difficulty' => 'Facil',
        ]);

        Course::create([
            'name' => 'Inversiones Avanzadas',
            'description' => 'Conocé herramientas financieras para invertir mejor.',
            'difficulty' => 'Medio',
        ]);

        Course::create([
            'name' => 'Inversiones Dificiles',
            'description' => 'Crypto.',
            'difficulty' => 'Dificil',
        ]);
    }
}
