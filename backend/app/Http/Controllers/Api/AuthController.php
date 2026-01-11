<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // استخدام التريت اللي إنت عملته (success & error)
    use ApiResponseTrait;

    /**
     * تسجيل مستخدم جديد
     * Milestone 1.3: Validation & Sanitization
     */
    public function register(Request $request): JsonResponse
    {
        // 1. Validation الصارم
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Sanitization (تنظيف البيانات قبل الحفظ)
        $user = User::create([
            'name'     => Str::title(trim(strip_tags($request->name))),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
        ]);

        // 3. الرد باستخدام الـ success function بتاعتك
        return $this->success($user, 'Account created successfully', 201);
    }

    /**
     * تسجيل الدخول
     * Milestone 1.3: Session-based Auth + Security
     */
    public function login(Request $request): JsonResponse
    {
        // 1. التحقق من المدخلات
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. محاولة تسجيل الدخول
        if (Auth::attempt($credentials)) {
            // 3. تأمين الجلسة (Session Fixation Protection)
            $request->session()->regenerate();

            return $this->success(Auth::user(), 'Logged in successfully');
        }

        // 4. رد الفشل باستخدام الـ error function بتاعتك
        return $this->error('Invalid credentials', [], 401);
    }

    /**
     * تسجيل الخروج
     * Milestone 1.3: Invalidation
     */
    public function logout(Request $request): JsonResponse
    {
        // 1. تسجيل الخروج من Guard الـ Web (لأننا شغالين Sessions)
        Auth::guard('web')->logout();

        // 2. تدمير الجلسة وتوليد توكن CSRF جديد للأمان
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->success(null, 'Logged out successfully');
    }
}