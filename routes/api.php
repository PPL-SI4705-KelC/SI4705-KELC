<?php

use App\Http\Controllers\Api\v1\LeaderboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/leaderboard', [LeaderboardController::class, 'index']);
    Route::get('/leaderboard/{user}/history', [LeaderboardController::class, 'history']);
});

