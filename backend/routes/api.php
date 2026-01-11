<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Skill;
/*
|--------------------------------------------------------------------------
| Public Routes (المسارات العامة)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Protected Routes (المسارات المحمية)
|--------------------------------------------------------------------------
*/
// المسارات دي مش هتشتغل إلا لو المستخدم عنده Session نشطة في الداتابيز
Route::middleware('auth:sanctum')->group(function () {
    
    // جلب بيانات المستخدم الحالي
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/skills', [AuthController::class, 'updateSkills']);

  });
 
// 1. مسار جلب المهارات (تأكد إنه موجود)
Route::get('/skills', function () {
  return Skill::all();
});