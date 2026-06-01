<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserExerciseController;
use App\Http\Controllers\WorkoutController;
use App\Models\UserExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function() {
    Route::post('login', 'login')->middleware('guest');
    Route::post('register', 'register')->middleware('guest');
    Route::post('logout', 'logout')->middleware('auth:api');
});

Route::get('exercise-list', [ExerciseController::class, 'index'])->middleware('auth:api');
Route::apiResource('workout', WorkoutController::class)->middleware('auth:api');
Route::apiResource('user-exercise', UserExerciseController::class)->except(['index'])->middleware('auth:api');
Route::get('report', [ReportController::class, 'index'])->middleware('auth:api');