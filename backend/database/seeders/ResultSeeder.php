<?php

namespace Database\Seeders;
use App\Models\Result;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Result::create([
            'user_id' => 1,
            'exercise_id' => 1,
            'is_correct' => true,
            'answered_at' => Carbon::now()->subMinutes(15),
        ]);

        Result::create([
            'user_id' => 1,
            'exercise_id' => 2,
            'is_correct' => false,
            'answered_at' => Carbon::now()->subMinutes(10),
        ]);

        Result::create([
            'user_id' => 1,
            'exercise_id' => 1,
            'is_correct' => true,
            'answered_at' => Carbon::now()->subMinutes(5),
        ]);
    }
}
