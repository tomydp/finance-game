<?php

use App\Http\Controllers\API\CourseApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/courses', CourseApiController::class);
