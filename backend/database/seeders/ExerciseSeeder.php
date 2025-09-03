<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        // 5 ejercicios por lección (MCQ/TF/Fill)
        $lessons = DB::table('lessons')->select('id', 'title')->get();

        foreach ($lessons as $lesson) {
            $this->seedForLesson((int)$lesson->id, (string)$lesson->title);
        }
    }

    private function seedForLesson(int $lessonId, string $title): void
    {
        $exercises = [
            // 1) MCQ contextual
            [
                'type' => 'mcq',
                'question' => "¿Cuál acción se alinea mejor con la lección '{$title}'?",
                'options' => [
                    "Aplicar '{$title}' en casos reales",
                    'Ignorar registros y métricas',
                    'Endeudarse sin plan',
                    'Apostar por azar',
                ],
                'correct' => "Aplicar '{$title}' en casos reales",
            ],
            // 2) True/False (genérico, claro)
            [
                'type' => 'true_false',
                'question' => 'Esta afirmación es correcta: practicar hábitos consistentes mejora los resultados financieros.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            // 3) Fill blank contextual
            [
                'type' => 'fill_blank',
                'question' => "Un concepto clave en '{$title}' es la __________.",
                'options' => null,
                'correct' => 'planificación',
            ],
            // 4) MCQ: primer paso
            [
                'type' => 'mcq',
                'question' => "Para comenzar con '{$title}', ¿qué harías primero?",
                'options' => [
                    'Definir metas y recopilar datos',
                    'Omitir el análisis',
                    'Gastar todo el presupuesto',
                    'Confiar en la suerte',
                ],
                'correct' => 'Definir metas y recopilar datos',
            ],
            // 5) MCQ: evaluación/métrica
            [
                'type' => 'mcq',
                'question' => '¿Qué métrica ayuda a evaluar progreso en una actividad financiera?',
                'options' => [
                    'Porcentaje de cumplimiento',
                    'Color favorito',
                    'Número aleatorio',
                    'Día de la semana',
                ],
                'correct' => 'Porcentaje de cumplimiento',
            ],
        ];

        foreach ($exercises as $e) {
            DB::table('exercises')->updateOrInsert(
                ['lesson_id' => $lessonId, 'question' => $e['question']],
                [
                    'lesson_id'      => $lessonId,
                    'type'           => $e['type'],
                    'question'       => $e['question'],
                    'options'        => isset($e['options']) && $e['options'] !== null ? json_encode($e['options']) : null,
                    'correct_answer' => $e['correct'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }
    }
}
