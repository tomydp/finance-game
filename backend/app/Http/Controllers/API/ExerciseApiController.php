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
     * Display a listing of the resource.
     */
    public function index(Lesson $lesson)
    {
        $exercises = $lesson->exercises()->orderBy('id')->get();
        return new ExerciseCollection($exercises);
    }
    
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
    
        // upsert (forzamos 1/0 para evitar castear mal)
        Result::updateOrCreate(
            ['user_id' => $user->id, 'exercise_id' => $exercise->id],
            ['is_correct' => $isCorrect ? 1 : 0, 'answered_at' => now()]
        );
    
        // progreso
        $lesson    = $exercise->lesson;
        $total     = $lesson->totalExercises();
        $completed = $lesson->completedExercises($user->id);
        $progress  = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
    
        // Log después del upsert
        Log::debug('Exercise submit persisted', [
            'user_id'        => $user->id,
            'exercise_id'    => $exercise->id,
            'result_saved'   => $isCorrect ? 1 : 0,
            'completed_cnt'  => $completed,
            'total_cnt'      => $total,
            'progress_pct'   => $progress,
            'request_id'     => $request->header('X-Request-Id'), // si lo enviás desde el FE
        ]);
    
        if ($completed === $total && $total > 0) {
            $lesson->unlockNextFor($user->id);
        }
    
        return response()->json([
            'correct'   => $isCorrect,
            'progress'  => $progress,
            'completed' => $total > 0 ? $completed === $total : false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson, Exercise $exercise)
    {
        abort_unless($exercise->lesson_id === $lesson->id, 404);
        return new ExerciseResource($exercise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
