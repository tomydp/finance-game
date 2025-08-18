<?php

use App\Http\Controllers\API\CourseApiController;
use App\Http\Controllers\API\ExerciseApiController;
use App\Http\Controllers\API\LessonApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// Registro y login
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login']);

// Cursos (solo lectura, por ahora)
Route::apiResource('courses', CourseApiController::class)
     ->only('index')                         // GET /api/courses
     ->names('api.courses');

// Lecciones de un curso (solo listado)
Route::apiResource('courses.lessons', LessonApiController::class)
     ->only('index')                         // GET /api/courses/{course}/lessons
     ->names('api.courses.lessons');

// Ejercicios por lección
Route::apiResource('lessons.exercises', ExerciseApiController::class)
     ->only(['index', 'show'])              // GET /api/lessons/{lesson}/exercises
     ->names('api.lessons.exercises');
