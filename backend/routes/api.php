<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (المسارات العامة)
|--------------------------------------------------------------------------
*/
// أي حد يقدر يوصل للمسارات دي عشان ينشئ حساب أو يسجل دخول
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


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

    // تسجيل الخروج وحذف الجلسة
    Route::post('/logout', [AuthController::class, 'logout']);
});