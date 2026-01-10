<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * تسجيل مستخدم جديد مع إنشاء جلسة.
     */
    public function register(Request $request)
    {
        // 1. التحقق من البيانات (Validation) - مستوى بروفشنال
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', 'in:client,freelancer'], // حصر الأدوار
        ]);

        // 2. إنشاء المستخدم وتشفير كلمة السر
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // 3. تفعيل الـ Session فوراً (تسجيل الدخول تلقائياً)
        Auth::login($user);

        // 4. الرد على الـ React
        return response()->json([
            'message' => 'Account created successfully',
            'user'    => $user
        ], 201);
    }

    /**
     * تسجيل الدخول والتحقق من الـ Sessions.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // تجديد الجلسة لمنع هجمات الـ Session Fixation
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Logged in successfully',
                'user'    => Auth::user()
            ]);
        }

        return response()->json([
            'errors' => ['email' => 'Invalid credentials']
        ], 422);
    }
}