<?php

namespace Database\Seeders;
use App\Models\UserAchievements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class UserAchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        UserAchievements::create([
            'user_id' => 1,
            'achievement_id' => 1,
            'earned_at' => Carbon::now()->subDays(2),
        ]);

        UserAchievements::create([
            'user_id' => 1,
            'achievement_id' => 2,
            'earned_at' => Carbon::now()->subDay(),
        ]);
    }
}
