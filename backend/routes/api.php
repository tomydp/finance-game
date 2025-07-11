<?php

use App\Http\Controllers\API\CourseApiController;
use App\Http\Controllers\API\ExerciseApiController;
use App\Http\Controllers\API\LessonApiController;
use Illuminate\Support\Facades\Route;

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
