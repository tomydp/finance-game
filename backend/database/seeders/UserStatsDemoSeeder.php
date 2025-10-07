<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserStatsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name'  => 'Usuario Demo',
            'email' => 'demo+'.Str::random(4).'@demo.com',
        ]);

        DB::transaction(function () use ($user) {
            // Si no hay lecciones/ejercicios, creamos unos mínimos sin factories
            if (Lesson::count() === 0) {
                $lesson1 = Lesson::create([
                    'course_id'   => 1, // ajusta si usás FK estricta
                    'title'       => 'Lección 1',
                    'description' => 'Intro',
                    'order'       => 1,
                    'status'      => Lesson::STATUS_ACTIVO,
                ]);
                $lesson2 = Lesson::create([
                    'course_id'   => 1,
                    'title'       => 'Lección 2',
                    'description' => 'Conceptos',
                    'order'       => 2,
                    'status'      => Lesson::STATUS_ACTIVO,
                ]);

                foreach ([$lesson1, $lesson2] as $lesson) {
                    Exercise::create([
                        'lesson_id'      => $lesson->id,
                        'type'           => 'mcq',
                        'question'       => '¿Pregunta 1?',
                        'options'        => json_encode(['A','B','C','D']),
                        'correct_answer' => 'A',
                        'explanation_md' => 'Explicación',
                        'status'         => 'activo',
                    ]);
                    Exercise::create([
                        'lesson_id'      => $lesson->id,
                        'type'           => 'true_false',
                        'question'       => '¿Verdadero?',
                        'options'        => null,
                        'correct_answer' => 'true',
                        'explanation_md' => 'Explicación',
                        'status'         => 'activo',
                    ]);
                }
            }

            $lessons = Lesson::with('exercises')->get();
            $today = Carbon::today();

            // Generamos 6 días de racha: hoy, ayer, ..., hoy-5
            for ($offset = 5; $offset >= 0; $offset--) {
                $day = $today->copy()->subDays($offset)->setTime(12, 0);

                foreach ($lessons as $lesson) {
                    // Para asegurar "lección completada", marcamos al menos un acierto por ejercicio
                    foreach ($lesson->exercises as $ex) {
                        Result::create([
                            'user_id'     => $user->id,
                            'exercise_id' => $ex->id,
                            'is_correct'  => true,
                            'answered_at' => $day,
                        ]);
                    }
                }
            }
        });
    }
}
