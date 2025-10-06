<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RankingDemoSeeder extends Seeder
{
    private const USER_COUNT = 30;
    private const EMAIL_MASK = 'ranking-demo+%02d@analytics-demo.test';
    private const DEFAULT_PASSWORD = 'password';
    private const WINDOW_DAYS = 30;

    public function run(): void
    {
        $exerciseIds = DB::table('exercises')->pluck('id')->all();

        if (empty($exerciseIds)) {
            $this->command?->warn('RankingDemoSeeder: no hay ejercicios cargados. Ejecutá primero los seeders base.');
            return;
        }

        DB::transaction(function () use ($exerciseIds) {
            $users = $this->seedUsers();
            $this->seedResults($users, $exerciseIds);
        });
    }

    private function seedUsers(): Collection
    {
        $now = Carbon::now();
        $passwordHash = Hash::make(self::DEFAULT_PASSWORD);
        $users = collect();

        for ($i = 1; $i <= self::USER_COUNT; $i++) {
            $email = sprintf(self::EMAIL_MASK, $i);
            $createdAt = $now->copy()
                ->subDays(rand(0, self::WINDOW_DAYS - 1))
                ->setTime(rand(8, 22), rand(0, 59), rand(0, 59));

            /** @var \App\Models\User $user */
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => "Ranking Demo {$i}",
                    'password'          => $passwordHash,
                    'is_admin'          => false,
                    'remember_token'    => Str::random(10),
                    'email_verified_at' => $createdAt,
                    'created_at'        => $createdAt,
                    'updated_at'        => $now,
                ]
            );

            $users->push($user);
        }

        return $users;
    }

    private function seedResults(Collection $users, array $exerciseIds): void
    {
        if ($users->isEmpty() || empty($exerciseIds)) {
            return;
        }

        $windowStart = Carbon::now()->subDays(self::WINDOW_DAYS)->startOfDay();
        $rows = [];

        foreach ($users as $user) {
            DB::table('results')
                ->where('user_id', $user->id)
                ->where('answered_at', '>=', $windowStart)
                ->delete();

            $skill = rand(45, 90) / 100; // probabilidad base de acierto

            for ($d = 0; $d < self::WINDOW_DAYS; $d++) {
                $day = Carbon::today()->subDays($d);

                if (rand(0, 100) < 35) {
                    continue; // día sin actividad
                }

                $answers = rand(8, 30);

                for ($i = 0; $i < $answers; $i++) {
                    $answeredAt = $day->copy()->setTime(rand(8, 23), rand(0, 59), rand(0, 59));
                    $exerciseId = $exerciseIds[array_rand($exerciseIds)];

                    $variance = rand(-12, 12) / 100; // +/- 12%
                    $probability = max(0.2, min(0.95, $skill + $variance));
                    $isCorrect = (mt_rand() / mt_getrandmax()) <= $probability ? 1 : 0;

                    $rows[] = [
                        'user_id'     => $user->id,
                        'exercise_id' => $exerciseId,
                        'is_correct'  => $isCorrect,
                        'answered_at' => $answeredAt,
                        'created_at'  => $answeredAt,
                        'updated_at'  => $answeredAt,
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('results')->insert($chunk);
        }
    }
}
