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

        $upsert = function (?int $lessonId, array $e): void {
            if (!$lessonId) return;

            DB::table('exercises')->updateOrInsert(
                ['lesson_id' => $lessonId, 'question' => $e['question']],
                [
                    'lesson_id'       => $lessonId,
                    'type'            => $e['type'],
                    'question'        => $e['question'],
                    'options'         => isset($e['options']) && $e['options'] !== null ? json_encode($e['options']) : null,
                    'correct_answer'  => $e['correct'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        };

        $addExercises = function (?int $lessonId, array $exercises) use ($upsert): void {
            foreach ($exercises as $e) {
                $upsert($lessonId, $e);
            }
        };

        // === Cursos base ===
        $cidFund = $idByCourse('Fundamentos Financieros');
        $cidInv  = $idByCourse('Introducción a Inversiones');

        // --- Fundamentos Financieros ---
        $addExercises($findLesson($cidFund, 'Armar presupuesto'), [
            [
                'type' => 'mcq',
                'question' => '¿Cuál es el primer paso para armar un presupuesto?',
                'options' => ['Registrar ingresos y gastos', 'Invertir en acciones', 'Solicitar un crédito', 'Comprar en cuotas'],
                'correct' => 'Registrar ingresos y gastos',
            ],
            [
                'type' => 'true_false',
                'question' => 'La regla 50/30/20 sugiere 50% necesidades, 30% deseos y 20% ahorro/deuda.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            [
                'type' => 'fill_blank',
                'question' => 'Un gasto de Netflix se clasifica como gasto de ____ en la mayoría de los presupuestos.',
                'options' => null,
                'correct' => 'deseo',
            ],
        ]);

        $addExercises($findLesson($cidFund, 'Ahorro e imprevistos'), [
            [
                'type' => 'mcq',
                'question' => '¿Cuántos meses de gastos suele cubrir un fondo de emergencia?',
                'options' => ['1-2', '3-6', '9-12', '12-24'],
                'correct' => '3-6',
            ],
            [
                'type' => 'true_false',
                'question' => 'Un fondo de emergencia debe ser altamente líquido y de bajo riesgo.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            [
                'type' => 'fill_blank',
                'question' => 'La prioridad es crear primero un fondo de ____ antes de invertir agresivamente.',
                'options' => null,
                'correct' => 'emergencia',
            ],
        ]);

        $addExercises($findLesson($cidFund, 'Objetivos SMART'), [
            [
                'type' => 'mcq',
                'question' => '¿Cuál de estos es un objetivo SMART?',
                'options' => [
                    'Ahorrar más dinero algún día',
                    'Ahorrar $10000 en 12 meses para una moto',
                    'Ser rico pronto',
                    'Ganar la lotería este año',
                ],
                'correct' => 'Ahorrar $10000 en 12 meses para una moto',
            ],
            [
                'type' => 'true_false',
                'question' => 'Los objetivos SMART incluyen una componente temporal definida.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            [
                'type' => 'fill_blank',
                'question' => 'SMART significa Específico, Medible, Alcanzable, Relevante y con ____.',
                'options' => null,
                'correct' => 'tiempo',
            ],
        ]);

        // --- Introducción a Inversiones ---
        $addExercises($findLesson($cidInv, 'Riesgo vs Retorno'), [
            [
                'type' => 'mcq',
                'question' => 'En general, a mayor riesgo esperado, el retorno esperado es...',
                'options' => ['Menor', 'Igual', 'Mayor', 'Nulo'],
                'correct' => 'Mayor',
            ],
            [
                'type' => 'true_false',
                'question' => 'La volatilidad es una medida de variación de precios y se usa como proxy de riesgo.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            [
                'type' => 'fill_blank',
                'question' => 'Una forma de manejar el riesgo es la ____, es decir, repartir entre activos.',
                'options' => null,
                'correct' => 'diversificación',
            ],
        ]);

        $addExercises($findLesson($cidInv, 'Instrumentos: PF, Bonos, Acciones'), [
            [
                'type' => 'mcq',
                'question' => '¿Cuál instrumento suele considerarse de menor riesgo?',
                'options' => ['Acciones', 'Bonos corporativos high-yield', 'Plazo fijo bancario', 'Criptoactivos volátiles'],
                'correct' => 'Plazo fijo bancario',
            ],
            [
                'type' => 'mcq',
                'question' => '¿Qué instrumento puede pagar cupones de interés periódicos?',
                'options' => ['Acciones de crecimiento', 'Bonos', 'Plazo fijo no renovable', 'Stablecoins'],
                'correct' => 'Bonos',
            ],
            [
                'type' => 'true_false',
                'question' => 'Las acciones suelen tener mayor potencial de retorno que los bonos a largo plazo.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
        ]);

        $addExercises($findLesson($cidInv, 'Diversificación básica'), [
            [
                'type' => 'true_false',
                'question' => 'Diversificar reduce el riesgo específico pero no elimina el riesgo de mercado.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            [
                'type' => 'mcq',
                'question' => '¿Qué combinación favorece la diversificación?',
                'options' => [
                    'Activos muy correlacionados',
                    'Activos con baja correlación',
                    'Un solo activo',
                    'Todo en efectivo',
                ],
                'correct' => 'Activos con baja correlación',
            ],
            [
                'type' => 'fill_blank',
                'question' => 'Para diversificar, buscamos activos con baja ____ entre sí.',
                'options' => null,
                'correct' => 'correlación',
            ],
        ]);

        // === Todos los "Crédito y Deuda Responsable N" ===
        $creditoCourses = DB::table('courses')
            ->where('name', 'LIKE', 'Crédito y Deuda Responsable%')
            ->get(['id', 'name']);

        foreach ($creditoCourses as $c) {
            // Lección 1
            $addExercises($findLesson($c->id, 'Cómo funciona el interés'), [
                [
                    'type' => 'mcq',
                    'question' => 'El interés compuesto implica que se capitalizan...',
                    'options' => ['Sólo los intereses', 'Interés sobre interés', 'Sólo el capital', 'Impuestos'],
                    'correct' => 'Interés sobre interés',
                ],
                [
                    'type' => 'true_false',
                    'question' => 'La tasa efectiva anual (TEA) suele ser mayor que la tasa nominal anual (TNA) si hay capitalización.',
                    'options' => ['true', 'false'],
                    'correct' => 'true',
                ],
                [
                    'type' => 'fill_blank',
                    'question' => 'Si la capitalización es mensual, hay 12 períodos de ____ al año.',
                    'options' => null,
                    'correct' => 'interés',
                ],
            ]);

            // Lección 2
            $addExercises($findLesson($c->id, 'Tarjetas y buenas prácticas'), [
                [
                    'type' => 'mcq',
                    'question' => '¿Qué práctica evita intereses en tarjeta?',
                    'options' => ['Pagar el mínimo', 'Pagar total antes del vencimiento', 'Financiar en 12 cuotas', 'Girar en descubierto'],
                    'correct' => 'Pagar total antes del vencimiento',
                ],
                [
                    'type' => 'true_false',
                    'question' => 'Pagar sólo el mínimo incrementa el costo total por intereses.',
                    'options' => ['true', 'false'],
                    'correct' => 'true',
                ],
                [
                    'type' => 'fill_blank',
                    'question' => 'Para evitar cargos, es clave conocer la fecha de ____ y de vencimiento.',
                    'options' => null,
                    'correct' => 'cierre',
                ],
            ]);
        }
    }
}
