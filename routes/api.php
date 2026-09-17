<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\WorkController;
use App\Http\Controllers\Api\WorkProgressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes -- mobile app (React Native)
|--------------------------------------------------------------------------
| Token auth via Sanctum. The web app's session-based login is untouched;
| this is a separate, additive surface over the same models/database.
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::get('works', [WorkController::class, 'index']);
    Route::get('works/{work}', [WorkController::class, 'show']);
    Route::get('works/{work}/stages', [WorkProgressController::class, 'stages']);
    Route::post('works/{work}/progress', [WorkProgressController::class, 'store']);

    Route::put('profile', [ProfileController::class, 'update']);
    Route::post('change-password', [ProfileController::class, 'changePassword']);
});
