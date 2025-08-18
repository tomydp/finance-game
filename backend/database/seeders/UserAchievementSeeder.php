<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('email', 'alumno@demo.com')->value('id');
        if (!$userId) return;

        $achievements = DB::table('achievements')->pluck('id', 'name');

        $pairs = [
            'Primer paso' => Carbon::now()->subDays(2),
            'Ahorrista'   => Carbon::now()->subDay(),
        ];

        foreach ($pairs as $name => $date) {
            $achId = $achievements[$name] ?? null;
            if ($achId) {
                DB::table('user_achievements')->updateOrInsert(
                    ['user_id' => $userId, 'achievement_id' => $achId],
                    [
                        'earned_at' => $date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
