<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkillController;
Route::middleware(['auth:sanctum'])
    ->prefix('public')
    ->group(function () {
        Route::get('skills', [SkillController::class, 'index']);
    });
