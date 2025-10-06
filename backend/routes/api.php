<?php

use App\Http\Controllers\API\AnalyticsRankingController;
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

Route::get('analytics/rankings', [AnalyticsRankingController::class, 'index'])
    ->name('api.analytics.rankings.index');

Route::apiResource('lessons.exercises', ExerciseApiController::class)
     ->only(['index', 'show'])        // GET /api/lessons/{lesson}/exercises
     ->names('api.lessons.exercises');

Route::post('/lessons/{id}/complete', [\App\Http\Controllers\API\LessonApiController::class, 'completar']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post(
        'exercises/{exercise}/submit', // POST /api/exercises/{id}/submit
        [ExerciseApiController::class, 'submit']
    )->name('api.exercises.submit');
});
