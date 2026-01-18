<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(RegisterRequest $request)
    {
        // استخدام DB Transaction عشان لو فيه خطأ في الـ assignRole ميكريتش يوزر ناقص
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => trim($request->name),
                'email'    => strtolower($request->email),
                'password' => $request->password, // هيتعمل له Hash أوتوماتيك في الموديل
            ]);

            // assignRole بناءً على الداتا اللي جاية من الـ Request
            $user->assignRole($request->role);

            // إنشاء بروفايل فاضي عشان ميرجعش null في الـ ProfileResource
            $user->profile()->create();

            Auth::login($user);

            $user->sendEmailVerificationNotification();

            return $this->success(
                ['user' => new UserResource($user->load('roles'))],
                'Account created successfully',
                201
            );
        });
    }

    public function login(LoginRequest $request)
    {
        // هو هيعمل الـ authenticate والـ throttle لوحده
        $request->authenticate(); 
    
        $request->session()->regenerate();
    
        return $this->success(
            ['user' => new UserResource(Auth::user()->load('roles', 'profile'))],
            'Logged in successfully'
        );
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return $this->success(null, 'Logged out successfully');
    }
}