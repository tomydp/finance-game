<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            ExerciseSeeder::class,
            AchievementSeeder::class,
            SimulationSeeder::class,
            ResultSeeder::class,
            UserAchievementSeeder::class,
        ]);
    }
}
