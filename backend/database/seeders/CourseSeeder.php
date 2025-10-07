<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['name' => 'Fundamentos Financieros',            'description' => 'Bases de presupuesto, ahorro y control de gastos.',                 'difficulty' => 'facil'],
            ['name' => 'Introducción a Inversiones',         'description' => 'Riesgo/retorno, instrumentos básicos y diversificación.',         'difficulty' => 'medio'],
            ['name' => 'Crédito y Deuda Responsable',        'description' => 'Score, tasas, interés compuesto y buenas prácticas.',             'difficulty' => 'medio'],
            ['name' => 'Ahorro e Imprevistos',               'description' => 'Fondo de emergencia, liquidez y hábitos de ahorro.',              'difficulty' => 'facil'],
            ['name' => 'Presupuesto Personal Avanzado',      'description' => 'Optimización 50/30/20, recategorización y seguimiento.',          'difficulty' => 'medio'],
            ['name' => 'Impuestos Personales Básicos',       'description' => 'Impuestos directos/indirectos, comprobantes y registros.',       'difficulty' => 'medio'],
            ['name' => 'Seguros Personales',                 'description' => 'Vida, salud, hogar; coberturas y evaluación de riesgos.',         'difficulty' => 'facil'],
            ['name' => 'Jubilación y Largo Plazo',           'description' => 'Aportes, capitalización, metas y horizonte >20 años.',            'difficulty' => 'dificil'],
            ['name' => 'Economía del Día a Día',             'description' => 'Inflación, tipo de cambio y poder adquisitivo.',                  'difficulty' => 'medio'],
            ['name' => 'Microemprendimientos',               'description' => 'Costos, precios, flujo de caja y punto de equilibrio.',           'difficulty' => 'dificil'],
        ];

        foreach ($courses as $c) {
            DB::table('courses')->updateOrInsert(
                ['name' => $c['name']],
                $c + [
                    'status'     => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
