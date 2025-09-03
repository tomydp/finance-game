<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // IDs de cursos base (si existen)
        $cidFund = DB::table('courses')->where('name', 'Fundamentos Financieros')->value('id');
        $cidInv  = DB::table('courses')->where('name', 'Introducción a Inversiones')->value('id');

        // Todas las variantes numeradas de "Crédito y Deuda Responsable ..."
        $creditoCourses = DB::table('courses')
            ->where('name', 'LIKE', 'Crédito y Deuda Responsable%')
            ->get(['id', 'name']);

        $rows = [];

        // Fundamentos (solo si existe)
        if ($cidFund) {
            $rows[] = ['course_id' => $cidFund, 'title' => 'Armar presupuesto',        'order' => 1];
            $rows[] = ['course_id' => $cidFund, 'title' => 'Ahorro e imprevistos',     'order' => 2];
            $rows[] = ['course_id' => $cidFund, 'title' => 'Objetivos SMART',          'order' => 3];
        }

        // Inversiones (solo si existe)
        if ($cidInv) {
            $rows[] = ['course_id' => $cidInv, 'title' => 'Riesgo vs Retorno',                 'order' => 1];
            $rows[] = ['course_id' => $cidInv, 'title' => 'Instrumentos: PF, Bonos, Acciones', 'order' => 2];
            $rows[] = ['course_id' => $cidInv, 'title' => 'Diversificación básica',            'order' => 3];
        }

        // Para cada "Crédito y Deuda Responsable N" ⇒ mismas 2 lecciones
        foreach ($creditoCourses as $c) {
            $rows[] = ['course_id' => $c->id, 'title' => 'Cómo funciona el interés',    'order' => 1];
            $rows[] = ['course_id' => $c->id, 'title' => 'Tarjetas y buenas prácticas', 'order' => 2];
        }

        foreach ($rows as $l) {
            DB::table('lessons')->updateOrInsert(
                ['course_id' => $l['course_id'], 'title' => $l['title']],
                $l + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
