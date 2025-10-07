<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseCollection;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExerciseApiController extends Controller
{
    /**
     * GET /api/lessons/{lesson}/exercises
     * Lista de ejercicios de una lección (orden ascendente por id).
     */
    public function index(Lesson $lesson)
    {
        $exercises = $lesson->exercises()->orderBy('id')->get();
        return new ExerciseCollection($exercises);
    }

    /**
     * POST /api/exercises/{exercise}/submit
     * Body: { answer: string|bool }
     */
    public function submit(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'answer' => 'required',
        ]);

        $user      = $request->user();
        $isCorrect = $exercise->checkAnswer($validated['answer']);

        // Log antes del upsert
        Log::debug('Exercise submit evaluated', [
            'user_id'     => optional($user)->id,
            'exercise_id' => $exercise->id,
            'lesson_id'   => $exercise->lesson_id,
            'is_correct'  => $isCorrect,
            'answer_type' => gettype($validated['answer']),
        ]);

        // Upsert (forzamos 1/0)
        Result::updateOrCreate(
            ['user_id' => $user->id, 'exercise_id' => $exercise->id],
            ['is_correct' => $isCorrect ? 1 : 0, 'answered_at' => now()]
        );

        // Progreso
        $lesson    = $exercise->lesson;
        $total     = $lesson->totalExercises();
        $completed = $lesson->completedExercises($user->id);
        $progress  = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        // Log después del upsert
        Log::debug('Exercise submit persisted', [
            'user_id'       => $user->id,
            'exercise_id'   => $exercise->id,
            'result_saved'  => $isCorrect ? 1 : 0,
            'completed_cnt' => $completed,
            'total_cnt'     => $total,
            'progress_pct'  => $progress,
            'request_id'    => $request->header('X-Request-Id'),
        ]);

        if ($total > 0 && $completed === $total) {
            $lesson->unlockNextFor($user->id);
        }

        // ✅ Feedback solo cuando falla
        $feedback = $isCorrect ? null : [
            'explanation_md' => $exercise->explanation_md,
        ];

        return response()->json([
            'correct'   => $isCorrect,
            'progress'  => $progress,
            'completed' => $total > 0 ? $completed === $total : false,
            'feedback'  => $feedback,
            'explanation_md' => $exercise->explanation_md,
        ]);
    }

    /**
     * GET /api/lessons/{lesson}/exercises/{exercise}
     */
    public function show(Lesson $lesson, Exercise $exercise)
    {
        abort_unless($exercise->lesson_id === $lesson->id, 404);
        return new ExerciseResource($exercise);
    }

    // Opcionales
    public function store(Request $request) { /* ... */ }
    public function update(Request $request, string $id) { /* ... */ }
    public function destroy(string $id) { /* ... */ }
}
