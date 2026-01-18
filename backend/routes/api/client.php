<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Client\ProjectController;
use App\Http\Controllers\Api\client\ProfileController;

Route::middleware(['auth:sanctum', 'role:client'])
    ->prefix('client')
    ->group(function () {

        Route::get('profile/me', [ProfileController::class, 'me']);
        Route::put('profile/me', [ProfileController::class, 'update']);

    });
