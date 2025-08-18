<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Primer paso', 'description' => 'Completa tu primera lección'],
            ['name' => 'Constancia',   'description' => 'Estudia 5 días seguidos'],
            ['name' => 'Ahorrista',    'description' => 'Aprueba 10 ejercicios'],
            ['name' => 'Inversor',     'description' => 'Termina el curso de Inversiones'],
        ];

        foreach ($rows as $r) {
            DB::table('achievements')->updateOrInsert(
                ['name' => $r['name']],
                $r + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
