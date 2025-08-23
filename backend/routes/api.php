<?php

use App\Http\Controllers\API\CourseApiController;
use App\Http\Controllers\API\ExerciseApiController;
use App\Http\Controllers\API\LessonApiController;
use Illuminate\Support\Facades\Route;

// Cursos (solo lectura)
Route::get('/courses', [CourseApiController::class, 'index'])
    ->name('api.courses.index'); // GET /api/courses

// Lecciones por curso (solo listado)
Route::get('/courses/{course}/lessons', [LessonApiController::class, 'index'])
    ->name('api.courses.lessons.index'); // GET /api/courses/{course}/lessons

// Ejercicios por lección
Route::get('/lessons/{lesson}/exercises', [ExerciseApiController::class, 'index'])
    ->name('api.lessons.exercises.index'); // GET /api/lessons/{lesson}/exercises

// Ejercicio individual (show)
Route::get('/lessons/{lesson}/exercises/{exercise}', [ExerciseApiController::class, 'show'])
    ->name('api.lessons.exercises.show'); // GET /api/lessons/{lesson}/exercises/{exercise}

// Protegidas por Sanctum
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Responder ejercicio
    Route::post('/exercises/{exercise}/submit', [ExerciseApiController::class, 'submit'])
        ->name('api.exercises.submit'); // POST /api/exercises/{id}/submit

    // Completar lección
    Route::post('/lessons/{lesson}/complete', [LessonApiController::class, 'complete'])
        ->name('api.lessons.complete'); // POST /api/lessons/{lesson}/complete
});

// routes/api.php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->get(
    '/courses/{course}/progress',
    [CourseApiController::class, 'progress']
)->name('api.courses.progress');
