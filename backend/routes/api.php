<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseApiController;
use App\Http\Controllers\API\ExerciseApiController;
use App\Http\Controllers\API\LessonApiController;
use App\Http\Controllers\API\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::post('login',    [AuthController::class, 'login'])->name('api.login');

Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify.api');

Route::post('email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'Tu email ya está verificado.'], 200);
    }
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Te reenviamos el correo de verificación.'], 200);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth:sanctum', 'email_verified_json'])->group(function () {
    Route::put('profile',  [AuthController::class, 'profile'])->name('api.profile');
    Route::post('logout',  [AuthController::class, 'logout'])->name('api.logout');

    // Protegidos (Sanctum + rate limit)
    Route::middleware('throttle:60,1')->group(function () {
        Route::post('exercises/{exercise}/submit', [ExerciseApiController::class, 'submit'])
            ->whereNumber('exercise')->name('api.exercises.submit');

        Route::post('lessons/{lesson}/complete', [LessonApiController::class, 'complete'])
            ->whereNumber('lesson')->name('api.lessons.complete');

        Route::get('courses/{course}/progress', [CourseApiController::class, 'progress'])
            ->whereNumber('course')->name('api.courses.progress');
    });
});

// Públicos (solo lectura)
Route::get('courses', [CourseApiController::class, 'index'])->name('api.courses.index');
Route::get('courses/{course}/lessons', [LessonApiController::class, 'index'])
    ->whereNumber('course')->name('api.courses.lessons.index');
Route::get('lessons/{lesson}/exercises', [ExerciseApiController::class, 'index'])
    ->whereNumber('lesson')->name('api.lessons.exercises.index');
Route::get('lessons/{lesson}/exercises/{exercise}', [ExerciseApiController::class, 'show'])
    ->whereNumber('lesson')->whereNumber('exercise')->name('api.lessons.exercises.show');
