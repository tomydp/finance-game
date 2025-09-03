<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Livewire\Exercise\ShowExercise;
use App\Livewire\Exercise\CreateExercise;
use App\Livewire\Exercise\EditExercise;

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard')->middleware('auth');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Course
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');

    //User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    //Lesson
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
});
require __DIR__.'/auth.php';
