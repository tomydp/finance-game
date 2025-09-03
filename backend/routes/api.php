<?php

use App\Http\Controllers\API\CourseApiController;
use App\Http\Controllers\API\ExerciseApiController;
use App\Http\Controllers\API\LessonApiController;
use Illuminate\Support\Facades\Route;

// Públicos (solo lectura)
Route::get('courses', [CourseApiController::class, 'index'])
    ->name('api.courses.index'); // GET /api/courses

Route::get('courses/{course}/lessons', [LessonApiController::class, 'index'])
    ->whereNumber('course')
    ->name('api.courses.lessons.index'); // GET /api/courses/{course}/lessons

Route::get('lessons/{lesson}/exercises', [ExerciseApiController::class, 'index'])
    ->whereNumber('lesson')
    ->name('api.lessons.exercises.index'); // GET /api/lessons/{lesson}/exercises

Route::get('lessons/{lesson}/exercises/{exercise}', [ExerciseApiController::class, 'show'])
    ->whereNumber('lesson')
    ->whereNumber('exercise')
    ->name('api.lessons.exercises.show'); // GET /api/lessons/{lesson}/exercises/{exercise}

// Protegidos (Sanctum + rate limit)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('exercises/{exercise}/submit', [ExerciseApiController::class, 'submit'])
        ->whereNumber('exercise')
        ->name('api.exercises.submit'); // POST /api/exercises/{id}/submit

    Route::post('lessons/{lesson}/complete', [LessonApiController::class, 'complete'])
        ->whereNumber('lesson')
        ->name('api.lessons.complete'); // POST /api/lessons/{lesson}/complete

    Route::get('courses/{course}/progress', [CourseApiController::class, 'progress'])
        ->whereNumber('course')
        ->name('api.courses.progress'); // GET /api/courses/{course}/progress
});
