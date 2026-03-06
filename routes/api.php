<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\AuthController; // Import AuthController sekalian biar rapi

// Rute Publik (Tidak butuh token)
Route::post('/login', [AuthController::class, 'login']);

// Rute Terlindungi (Wajib pakai token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // API Projects (Tanpa method destroy)
    Route::apiResource('projects', ProjectController::class)->except(['destroy']);

    // API Tasks
    Route::apiResource('tasks', TaskController::class);
});
