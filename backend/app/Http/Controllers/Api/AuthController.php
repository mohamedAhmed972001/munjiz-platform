<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function csrf()
    {
        return response()->noContent(204);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->only(['name','email','password','role']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($data['role']);

        // $user->profile()->create([
        //     'title' => null,
        //     'bio' => null,
        // ]);

        event(new Registered($user));

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 'Registered successfully. Please verify your email.', 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email','password');

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'email_verified_at' => $user->email_verified_at,
            ]
        ], 'Logged in successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->success(null, 'Logged out successfully.');
    }

    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->error('Email already verified.', [], 400);
        }

        $user->sendEmailVerificationNotification();

        return $this->success(null, 'Verification email sent.');
    }

    public function verifyEmail(Request $request)
    {
        if (! $request->user()) {
            return $this->error('Unauthenticated', [], 401);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return $this->success(null, 'Email already verified.');
        }

        $request->user()->markEmailAsVerified();

        return $this->success(null, 'Email verified successfully.');
    }

    public function assignRole(Request $request, User $user)
    {
        $this->authorize('assignRole', $user);

        $role = $request->input('role');

        if (! in_array($role, ['admin','client','freelancer'])) {
            return $this->error('Invalid role.', [], 422);
        }

        $user->role = $role;
        $user->save();

        return $this->success([
            'user' => [
                'id' => $user->id,
                'role' => $user->role,
            ]
        ], 'Role updated.');
    }
}
