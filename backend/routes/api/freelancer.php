<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Freelancer\ProfileController;
use App\Http\Controllers\Api\Freelancer\SkillController;
use App\Http\Controllers\Api\Freelancer\PortfolioController;

Route::middleware(['auth:sanctum', 'role:freelancer'])
  ->prefix('freelancer')
  ->group(function () {

    Route::get('profile/me', [ProfileController::class, 'me']);
    Route::put('profile/me', [ProfileController::class, 'update']);

    Route::get('profile/me/skills', [SkillController::class, 'index']);
    Route::post('profile/me/skills', [SkillController::class, 'attach']);
    Route::delete('profile/me/skills/{skill}', [SkillController::class, 'detach']);
    // إضافة عمل جديد (POST)
    Route::post('portfolios', [PortfolioController::class, 'store']);
    // حذف عمل (DELETE) - لاحظ استخدام الـ ID هنا
    Route::delete('portfolios/{portfolio}', [PortfolioController::class, 'destroy']);
  });
