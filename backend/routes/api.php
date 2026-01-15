<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {

    // Register → بدون session مشكلة
    Route::post('register', [AuthController::class, 'register']);

    // Login → يحتاج session
    Route::post('login', [AuthController::class, 'login'])
         ->middleware('throttle:5,1'); 

    // Logout → auth:sanctum + session
    Route::post('logout', [AuthController::class, 'logout'])
         ->middleware('auth:sanctum');
});
