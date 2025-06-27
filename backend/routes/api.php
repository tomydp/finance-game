<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\AuthenticatedSessionController;

Route::post('/register', [RegisteredUserController::class, 'store'])
     ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
     ->middleware('guest');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $req) => $req->user());
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
