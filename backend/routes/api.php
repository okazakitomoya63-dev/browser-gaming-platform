<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GameController;

Route::prefix('v1')->group(function () {
    // Route Public (Tanpa Token)
    Route::post('/auth/signin', [AuthController::class, 'signin']);
    Route::post('/auth/signup', [AuthController::class, 'signup']);

    // Route Protected (Harus Login / Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/signout', [AuthController::class, 'signout']);

        // Admins Endpoint
        Route::get('/admins', [UserController::class, 'getAdmins']);

        // Users Endpoint
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get('/users/{username}', [UserController::class, 'show']);

        // Games Endpoint
        Route::get('/games', [GameController::class, 'index']);
        Route::post('/games', [GameController::class, 'store']);
        Route::get('/games/{slug}', [GameController::class, 'show']);
        Route::post('/games/{slug}/upload', [GameController::class, 'upload']);
        Route::put('/games/{slug}', [GameController::class, 'update']);
        Route::delete('/games/{slug}', [GameController::class, 'destroy']);
        
        // Scores Endpoint
        Route::get('/games/{slug}/scores', [GameController::class, 'getScores']);
        Route::post('/games/{slug}/scores', [GameController::class, 'postScore']);
    });
});