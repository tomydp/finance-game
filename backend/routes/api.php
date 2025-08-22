<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\CourseApiController;
use App\Http\Controllers\API\ExerciseApiController;
use App\Http\Controllers\API\LessonApiController;

Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login',    [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $r) => $r->user());
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

// Cursos (solo lectura, por ahora)
Route::apiResource('courses', CourseApiController::class)
     ->only('index')                         // GET /api/courses
     ->names('api.courses');

// Lecciones de un curso (solo listado)
Route::apiResource('courses.lessons', LessonApiController::class)
     ->only('index')                         // GET /api/courses/{course}/lessons
     ->names('api.courses.lessons');

Route::apiResource('lessons.exercises', ExerciseApiController::class)
     ->only(['index', 'show'])        // GET /api/lessons/{lesson}/exercises
     ->names('api.lessons.exercises');

Route::middleware('auth:sanctum')->group(function () {
    Route::post(
        'exercises/{exercise}/submit', // POST /api/exercises/{id}/submit
        [ExerciseApiController::class, 'submit']
    )->name('api.exercises.submit');
});


