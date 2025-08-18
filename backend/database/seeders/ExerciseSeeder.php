<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $idByCourse = fn(string $name) => DB::table('courses')->where('name', $name)->value('id');
        $findLesson = function (int $courseId, string $title): ?int {
            return DB::table('lessons')
                ->where('course_id', $courseId)
                ->where('title', $title)
                ->value('id');
        };

        // Cursos base
        $cidFund = $idByCourse('Fundamentos Financieros');
        $cidInv  = $idByCourse('Introducción a Inversiones');

        // 1) Fundamentos
        $this->upsertExercise($findLesson($cidFund, 'Armar presupuesto'), [
            'type' => 'mcq',
            'question' => '¿Cuál es el primer paso para armar un presupuesto?',
            'options' => ['Registrar ingresos y gastos', 'Invertir en acciones', 'Solicitar un crédito', 'Comprar en cuotas'],
            'correct' => 'Registrar ingresos y gastos',
        ]);

        $this->upsertExercise($findLesson($cidFund, 'Ahorro e imprevistos'), [
            'type' => 'true_false',
            'question' => 'Un fondo de emergencia ideal cubre entre 3 y 6 meses de gastos.',
            'options' => ['true', 'false'],
            'correct' => 'true',
        ]);

        $this->upsertExercise($findLesson($cidFund, 'Objetivos SMART'), [
            'type' => 'fill_blank',
            'question' => 'Los objetivos SMART deben ser Específicos, Medibles, Alcanzables, Relevantes y con _____ de tiempo.',
            'options' => null,
            'correct' => 'límites',
        ]);

        // 2) Inversiones
        $this->upsertExercise($findLesson($cidInv, 'Riesgo vs Retorno'), [
            'type' => 'mcq',
            'question' => 'En general, a mayor riesgo esperado, el retorno esperado es...',
            'options' => ['Menor', 'Igual', 'Mayor', 'Nulo'],
            'correct' => 'Mayor',
        ]);

        $this->upsertExercise($findLesson($cidInv, 'Instrumentos: PF, Bonos, Acciones'), [
            'type' => 'mcq',
            'question' => '¿Cuál instrumento suele considerarse de menor riesgo?',
            'options' => ['Acciones', 'Bonos corporativos high-yield', 'Plazo fijo bancario', 'Criptoactivos volátiles'],
            'correct' => 'Plazo fijo bancario',
        ]);

        $this->upsertExercise($findLesson($cidInv, 'Diversificación básica'), [
            'type' => 'true_false',
            'question' => 'Diversificar reduce riesgo específico sin eliminar el riesgo de mercado.',
            'options' => ['true', 'false'],
            'correct' => 'true',
        ]);

        // 3) TODOS los "Crédito y Deuda Responsable N"
        $creditoCourses = DB::table('courses')
            ->where('name', 'LIKE', 'Crédito y Deuda Responsable%')
            ->get(['id', 'name']);

        foreach ($creditoCourses as $c) {
            // Cómo funciona el interés
            $this->upsertExercise($findLesson($c->id, 'Cómo funciona el interés'), [
                'type' => 'mcq',
                'question' => 'El interés compuesto implica que se capitalizan...',
                'options' => ['Sólo los intereses', 'Interés sobre interés', 'Sólo el capital', 'Impuestos'],
                'correct' => 'Interés sobre interés',
            ]);

            // Tarjetas y buenas prácticas
            $this->upsertExercise($findLesson($c->id, 'Tarjetas y buenas prácticas'), [
                'type' => 'mcq',
                'question' => '¿Qué práctica evita intereses en tarjeta?',
                'options' => ['Pagar el mínimo', 'Pagar total antes del vencimiento', 'Financiar en 12 cuotas', 'Girar en descubierto'],
                'correct' => 'Pagar total antes del vencimiento',
            ]);
        }
    }

    private function upsertExercise(?int $lessonId, array $data): void
    {
        if (!$lessonId) {
            return; // si por algún motivo la lección no existe, salteamos
        }

        DB::table('exercises')->updateOrInsert(
            ['lesson_id' => $lessonId, 'question' => $data['question']],
            [
                'lesson_id' => $lessonId,
                'type' => $data['type'],
                'question' => $data['question'],
                'options' => $data['options'] ? json_encode($data['options']) : null,
                'correct_answer' => $data['correct'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
