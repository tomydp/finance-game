<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // 3 lecciones por cada curso (títulos relacionados al tema)
        $lessonMap = [
            'Fundamentos Financieros'       => ['Armar presupuesto', 'Control de gastos', 'Objetivos SMART'],
            'Introducción a Inversiones'    => ['Riesgo vs retorno', 'Instrumentos básicos', 'Diversificación simple'],
            'Crédito y Deuda Responsable'   => ['Interés simple y compuesto', 'Tarjetas y vencimientos', 'Plan de desendeudamiento'],
            'Ahorro e Imprevistos'          => ['Fondo de emergencia', 'Liquidez y seguridad', 'Hábitos de ahorro'],
            'Presupuesto Personal Avanzado' => ['Regla 50/30/20', 'Recategorización de gastos', 'Seguimiento mensual'],
            'Impuestos Personales Básicos'  => ['Directos e indirectos', 'Comprobantes y registros', 'Planificación básica'],
            'Seguros Personales'            => ['Vida y salud', 'Hogar y bienes', 'Evaluar coberturas'],
            'Jubilación y Largo Plazo'      => ['Capitalización y aportes', 'Horizonte temporal', 'Inflación y retiro'],
            'Economía del Día a Día'        => ['Inflación y precios', 'Tipo de cambio', 'Poder de compra'],
            'Microemprendimientos'          => ['Costos fijos/variables', 'Precio y margen', 'Flujo de caja'],
        ];

        $courseIds = DB::table('courses')->pluck('id', 'name'); // name => id

        foreach ($lessonMap as $courseName => $titles) {
            $courseId = $courseIds[$courseName] ?? null;
            if (!$courseId) {
                continue;
            }
            $order = 1;
            foreach ($titles as $t) {
                DB::table('lessons')->updateOrInsert(
                    ['course_id' => $courseId, 'title' => $t],
                    [
                        'course_id'  => $courseId,
                        'title'      => $t,
                        'order'      => $order++,
                        'status'     => 'activo',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
