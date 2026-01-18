<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\Traits\ApiResponseTrait; // استدعاء التريت الموحد
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. استدعاء الـ Trait الناقص
class ProfileController extends Controller
{
    use ApiResponseTrait,AuthorizesRequests; // تفعيل التريت عشان نستخدم ميثود success
    public function me(Request $request)
    {
        // استخدام load لتقليل الـ queries للداتابيز
        $user = $request->user()->load(['roles', 'profile']);

        return $this->success([
            'user'    => new UserResource($user),
            'profile' => $user->profile ? new ProfileResource($user->profile) : null,
        ], 'Client info retrieved successfully');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // فالييشن خاص ببيانات العميل فقط
        $validated = $request->validate([
            'bio'      => ['nullable', 'string', 'max:1000'],
            'country'  => ['nullable', 'string'],
            'timezone' => ['nullable', 'string'],
        ]);

        // نجيب البروفايل أو نكريه لو مش موجود عشان نبعته للـ Policy
        $profile = $user->profile ?: $user->profile()->create(['user_id' => $user->id]);

        // التأكد من الملكية عبر الـ Policy
        $this->authorize('update', $profile);

        $profile->update($validated);

        return $this->success(
            new ProfileResource($profile),
            'Client profile updated successfully'
        );
    }
}