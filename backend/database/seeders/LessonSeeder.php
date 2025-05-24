<?php

namespace Database\Seeders;
use App\Models\Lesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lesson::create([
            'course_id' => 1,
            'title' => '¿Qué son los ingresos?',
            'order' => 1,
        ]);

        Lesson::create([
            'course_id' => 1,
            'title' => 'Gastos fijos y variables',
            'order' => 2,
        ]);
    }
}
