<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        // Crea ejercicios base para cada lección existente
        $lessons = DB::table('lessons')->select('id', 'title')->get();

        foreach ($lessons as $lesson) {
            $this->seedForLesson((int) $lesson->id, (string) $lesson->title);
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
                'correct_answer' => "Aplicar '{$title}' en casos reales",
                'explanation_md' => "Aplicar los conocimientos de **{$title}** en la práctica fortalece tu educación financiera.",
            ],
            // 2) True/False
            [
                'type' => 'true_false',
                'question' => 'Esta afirmación es correcta: practicar hábitos consistentes mejora los resultados financieros.',
                'options' => ['true', 'false'],
                'correct_answer' => 'true',
                'explanation_md' => 'Los hábitos financieros saludables sostenidos en el tiempo generan mejores resultados.',
            ],
            // 3) Fill blank contextual
            [
                'type' => 'fill_blank',
                'question' => "Un concepto clave en '{$title}' es la ____.",
                'options' => null,
                'correct_answer' => json_encode(['planificación', 'organización']),
                'explanation_md' => "La **planificación** es esencial para anticipar decisiones y manejar mejor tus recursos.",
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
                'correct_answer' => 'Definir metas y recopilar datos',
                'explanation_md' => 'El primer paso siempre es **definir tus objetivos** y entender tu situación actual.',
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
                'correct_answer' => 'Porcentaje de cumplimiento',
                'explanation_md' => 'Medir el **porcentaje de cumplimiento** permite evaluar el progreso hacia tus metas.',
            ],
        ];

        foreach ($exercises as $e) {
            DB::table('exercises')->updateOrInsert(
                [
                    'lesson_id' => $lessonId,
                    'question' => $e['question'],
                ],
                [
                    'lesson_id'      => $lessonId,
                    'type'           => $e['type'],
                    'question'       => $e['question'],
                    'options'        => isset($e['options']) && $e['options'] !== null ? json_encode($e['options']) : null,
                    'correct_answer' => $e['correct_answer'],
                    'explanation_md' => $e['explanation_md'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }
    }
}