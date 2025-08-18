<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            AchievementSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            ExerciseSeeder::class,
            ResultSeeder::class,
            SimulationSeeder::class,
            UserAchievementSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
