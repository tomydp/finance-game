<?php

use App\Http\Controllers\API\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ExerciseController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\AnalyticsDashboard;

Route::get('/', function () {
    // En SPA lo más común:
    return redirect()->away(config('app.frontend_url', 'http://localhost:5173'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify.web');

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');

    // Lessons
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');

    // Exercises (Livewire)
    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');

    // Analytics (Livewire)
    Route::get('/analytics', fn () => view('analytics.index'))->name('analytics.index');
});

require __DIR__ . '/auth.php';
