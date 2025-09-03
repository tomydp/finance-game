<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseCollection;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Result;
use Illuminate\Http\Request;

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

    // upsert respetando tu esquema
    Result::updateOrCreate(
        [
            'user_id'     => $user->id,
            'exercise_id' => $exercise->id,
        ],
        [
            'is_correct'  => $isCorrect,
            'answered_at' => now(),
        ]
    );

    // progreso
    $lesson    = $exercise->lesson;
    $total     = $lesson->totalExercises();
    $completed = $lesson->completedExercises($user->id);   // filtrará por is_correct=1
    $progress  = round(($completed / $total) * 100);

    if ($completed === $total) {
        $lesson->unlockNextFor($user->id);
    }

    return response()->json([
        'correct'   => $isCorrect,
        'progress'  => $progress,
        'completed' => $completed === $total,
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
