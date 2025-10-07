<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AnalyticsDemoSeeder extends Seeder
{
    private const DAYS_WINDOW        = 30;
    private const DEMO_USER_COUNT    = 120;
    private const DEMO_EMAIL_PATTERN = 'analytics-demo+%03d@analytics-demo.test';
    private const ADMIN_EMAIL        = 'analytics-admin@analytics-demo.test';
    private const TOP_COURSE_NAME    = 'Introducción a Inversiones';

    public function run(): void
    {
        DB::transaction(function () {
            $this->ensureDemoContent();
            $exercisesByCourse = $this->fetchExercisesByCourse();
            if ($exercisesByCourse->isEmpty()) {
                $this->command?->warn('AnalyticsDemoSeeder: no se pudieron generar ejercicios demo.');
                return;
            }

            $users = $this->createDemoUsers();
            if ($users->isEmpty()) {
                return;
            }

            $demoUserIds = $users->pluck('id')->all();
            $this->purgeExistingDemoData($demoUserIds);

            $this->seedSessions($users);
            $this->seedResults($users, $exercisesByCourse);
        });
    }

    private function ensureDemoContent(): void
    {
        $now = now();

        $courses = [
            ['name' => 'Fundamentos Financieros',     'description' => 'Bases de presupuesto, ahorro y control de gastos.',   'difficulty' => 'facil'],
            ['name' => self::TOP_COURSE_NAME,         'description' => 'Riesgo/retorno, instrumentos y diversificación.',    'difficulty' => 'medio'],
            ['name' => 'Crédito y Deuda Responsable', 'description' => 'Score, tasas e interés compuesto.',                   'difficulty' => 'medio'],
            ['name' => 'Ahorro e Imprevistos',        'description' => 'Fondo de emergencia, liquidez y hábitos saludables.', 'difficulty' => 'facil'],
        ];

        foreach ($courses as $course) {
            DB::table('courses')->updateOrInsert(
                ['name' => $course['name']],
                [
                    'description' => $course['description'],
                    'difficulty'  => $course['difficulty'],
                    'status'      => 'activo',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );
        }

        $lessonTitles = [
            'Fundamentos Financieros'     => ['Armar presupuesto', 'Control de gastos', 'Objetivos SMART'],
            self::TOP_COURSE_NAME         => ['Riesgo vs retorno', 'Instrumentos básicos', 'Diversificación simple'],
            'Crédito y Deuda Responsable' => ['Interés compuesto', 'Tarjetas y vencimientos', 'Plan de desendeudamiento'],
            'Ahorro e Imprevistos'        => ['Fondo de emergencia', 'Liquidez y seguridad', 'Hábitos de ahorro'],
        ];

        $courseIds = DB::table('courses')->pluck('id', 'name');

        foreach ($lessonTitles as $courseName => $titles) {
            $courseId = $courseIds[$courseName] ?? null;
            if (!$courseId) {
                continue;
            }

            foreach ($titles as $index => $title) {
                DB::table('lessons')->updateOrInsert(
                    ['course_id' => $courseId, 'title' => $title],
                    [
                        'description' => "Contenido base {$title}",
                        'order'       => $index + 1,
                        'status'      => 'activo',
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]
                );

                $lessonId = DB::table('lessons')
                    ->where('course_id', $courseId)
                    ->where('title', $title)
                    ->value('id');

                if ($lessonId) {
                    $this->ensureExercisesForLesson((int) $lessonId, $title, $now);
                }
            }
        }
    }

    private function ensureExercisesForLesson(int $lessonId, string $title, Carbon $timestamp): void
    {
        $templates = [
            [
                'type'    => 'mcq',
                'question'=> "(Opción correcta) ¿Qué acción refleja mejor la lección '{$title}'?",
                'options' => [
                    "Aplicar '{$title}' en una situación real",
                    'Ignorar el seguimiento de métricas',
                    'Aumentar gastos sin revisar',
                    'Dejar todo al azar',
                ],
                'correct' => "Aplicar '{$title}' en una situación real",
            ],
            [
                'type'    => 'true_false',
                'question'=> '(Verdadero/Falso) Practicar hábitos constantes mejora los resultados financieros.',
                'options' => ['true', 'false'],
                'correct' => 'true',
            ],
            [
                'type'    => 'fill_blank',
                'question'=> "(Completar) Un concepto clave en '{$title}' es la __________.",
                'options' => null,
                'correct' => 'planificación',
            ],
            [
                'type'    => 'mcq',
                'question'=> "(Opción correcta) Para comenzar con '{$title}', ¿cuál es el primer paso recomendado?",
                'options' => [
                    'Definir metas y recopilar información',
                    'Gastar sin control',
                    'Posponer indefinidamente',
                    'Depender únicamente de la suerte',
                ],
                'correct' => 'Definir metas y recopilar información',
            ],
            [
                'type'    => 'mcq',
                'question'=> '(Opción correcta) ¿Qué indicador ayuda a evaluar el progreso financiero?',
                'options' => [
                    'Porcentaje de cumplimiento',
                    'Color favorito',
                    'Número aleatorio',
                    'Día de la semana',
                ],
                'correct' => 'Porcentaje de cumplimiento',
            ],
        ];

        foreach ($templates as $template) {
            DB::table('exercises')->updateOrInsert(
                ['lesson_id' => $lessonId, 'question' => $template['question']],
                [
                    'lesson_id'      => $lessonId,
                    'type'           => $template['type'],
                    'question'       => $template['question'],
                    'options'        => $template['options'] ? json_encode($template['options'], JSON_UNESCAPED_UNICODE) : null,
                    'correct_answer' => $template['correct'],
                    'explanation_md' => null,
                    'status'         => 'activo',
                    'created_at'     => $timestamp,
                    'updated_at'     => $timestamp,
                ]
            );
        }
    }

    private function createDemoUsers(): Collection
    {
        $now          = now();
        $passwordHash = Hash::make('password');

        for ($i = 1; $i <= self::DEMO_USER_COUNT; $i++) {
            $email = sprintf(self::DEMO_EMAIL_PATTERN, $i);
            $createdAt = Carbon::now()
                ->subDays(rand(0, self::DAYS_WINDOW - 1))
                ->setTime(rand(8, 22), rand(0, 59), rand(0, 59));

            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name'              => "Demo User {$i}",
                    'password'          => $passwordHash,
                    'is_admin'          => 0,
                    'remember_token'    => Str::random(10),
                    'email_verified_at' => $createdAt,
                    'created_at'        => $createdAt,
                    'updated_at'        => $now,
                ]
            );
        }

        DB::table('users')->updateOrInsert(
            ['email' => self::ADMIN_EMAIL],
            [
                'name'              => 'Analytics Admin',
                'password'          => $passwordHash,
                'is_admin'          => 1,
                'remember_token'    => Str::random(10),
                'email_verified_at' => $now->copy()->subDays(15),
                'created_at'        => $now->copy()->subDays(15),
                'updated_at'        => $now,
            ]
        );

        return User::where('email', 'like', 'analytics-demo+%@analytics-demo.test')
            ->orWhere('email', self::ADMIN_EMAIL)
            ->get();
    }

    private function fetchExercisesByCourse(): Collection
    {
        $rows = DB::table('exercises as e')
            ->join('lessons as l', 'l.id', '=', 'e.lesson_id')
            ->join('courses as c', 'c.id', '=', 'l.course_id')
            ->select('c.name as course', 'e.id as exercise_id', 'e.type')
            ->get();

        return $rows
            ->groupBy('course')
            ->map(fn ($group) => $group->map(fn ($row) => [
                'id'   => (int) $row->exercise_id,
                'type' => (string) $row->type,
            ]));
    }

    private function purgeExistingDemoData(array $userIds): void
    {
        if (empty($userIds)) {
            return;
        }

        DB::table('sessions')->whereIn('user_id', $userIds)->delete();
        DB::table('results')->whereIn('user_id', $userIds)->delete();
    }

    private function seedSessions(Collection $users): void
    {
        $rows   = [];
        $today  = Carbon::today();
        $demoUsers = $users->reject(fn (User $user) => $user->is_admin);

        for ($offset = 0; $offset < self::DAYS_WINDOW; $offset++) {
            $day = (clone $today)->subDays($offset);

            $dailyUsers = $demoUsers->shuffle()->take(rand(45, 110));

            foreach ($dailyUsers as $user) {
                $lastActivity = (clone $day)->setTime(rand(8, 23), rand(0, 59), rand(0, 59));

                $rows[] = [
                    'id'            => Str::random(40),
                    'user_id'       => $user->id,
                    'ip_address'    => '10.0.' . rand(0, 200) . '.' . rand(2, 254),
                    'user_agent'    => 'Mozilla/5.0 (AnalyticsDemoSeeder)',
                    'payload'       => base64_encode(serialize([])),
                    'last_activity' => $lastActivity->timestamp,
                ];
            }
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('sessions')->insert($chunk);
        }
    }

    private function seedResults(Collection $users, Collection $exercisesByCourse): void
    {
        $demoUsers = $users->reject(fn (User $user) => $user->is_admin);
        if ($demoUsers->isEmpty()) {
            return;
        }

        $today = Carbon::today();

        $allExercises = $exercisesByCourse->flatten(1);
        if ($allExercises->isEmpty()) {
            return;
        }

        $topCourseExercises = $exercisesByCourse[self::TOP_COURSE_NAME] ?? $allExercises;

        $rows = [];

        for ($offset = 0; $offset < self::DAYS_WINDOW; $offset++) {
            $day = (clone $today)->subDays($offset);
            $activeUsers = $demoUsers->shuffle()->take(rand(35, 95));

            foreach ($activeUsers as $user) {
                $answers = rand(3, 10);

                for ($attempt = 0; $attempt < $answers; $attempt++) {
                    /** @var Collection|array<int, array{id:int,type:string}> $pool */
                    $poolCollection = ($offset < 7 && rand(0, 100) < 65)
                        ? $topCourseExercises
                        : $allExercises;

                    if (!$poolCollection instanceof Collection) {
                        $poolCollection = collect($poolCollection);
                    }

                    if ($poolCollection->isEmpty()) {
                        $poolCollection = $allExercises;
                    }

                    if ($poolCollection->isEmpty()) {
                        continue;
                    }

                    /** @var array{id:int,type:string} $exercise */
                    $exercise = $poolCollection->random();

                    $answeredAt = (clone $day)->setTime(rand(7, 22), rand(0, 59), rand(0, 59));

                    $baseAccuracy = $offset < 7 ? 0.78 : 0.72;
                    $variance     = rand(-8, 8) / 100;
                    $probability  = max(0.55, min(0.92, $baseAccuracy + $variance));
                    $isCorrect    = (mt_rand() / mt_getrandmax()) <= $probability ? 1 : 0;

                    $rows[] = [
                        'user_id'     => $user->id,
                        'exercise_id' => $exercise['id'],
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
