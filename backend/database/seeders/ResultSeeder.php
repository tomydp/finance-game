<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', 'alumno@demo.com')->value('id');
        if (!$userId) return;

        $exerciseIds = DB::table('exercises')->pluck('id');

        $now = Carbon::now();
        foreach ($exerciseIds as $i => $exId) {
            DB::table('results')->updateOrInsert(
                ['user_id' => $userId, 'exercise_id' => $exId],
                [
                    'user_id' => $userId,
                    'exercise_id' => $exId,
                    'is_correct' => ($i % 2 === 0) ? 1 : 0,
                    'answered_at' => $now->copy()->subMinutes(5 * $i),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
