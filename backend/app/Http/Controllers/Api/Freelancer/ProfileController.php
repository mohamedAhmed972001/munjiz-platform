<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource; // تأكد من استدعاء الـ Resource بتاعنا
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait; // استدعاء التريت
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. استدعاء الـ Trait الناقص
class ProfileController extends Controller
{
  use ApiResponseTrait,AuthorizesRequests; // تفعيل التريت عشان نستخدم ميثود success

  public function me(Request $request)
  {
    // 1. هنجيب اليوزر مع الرولز والبروفايل في query واحدة عشان السرعة
    $user = $request->user()->load(['roles', 'profile']);

    return response()->json([
      'status' => true,
      'data' => [
        // بنستخدم الـ Resource اللي عملناه سوا عشان يظهر الـ role name
        'user'    => new UserResource($user),
        'profile' => $user->profile ? new ProfileResource($user->profile) : null,
      ],
    ]);
  }
  public function update(Request $request)
  {
      $user = $request->user();

      // 1. Validation خاص بالفريلانسر
      $validated = $request->validate([
          'title'       => ['required', 'string', 'max:255'],
          'bio'         => ['nullable', 'string', 'max:1000'],
          'hourly_rate' => ['required', 'numeric', 'min:0'],
          'country'     => ['nullable', 'string'],
          'timezone'    => ['nullable', 'string'],
      ]);

      // 2. بنجيب البروفايل أو نكریته لو مش موجود
      $profile = $user->profile ?: $user->profile()->create(['user_id' => $user->id]);

      // 3. التشيك بتاع الـ Policy (دلوقتي المتغير $profile بقى متعرف)
      $this->authorize('update', $profile);

      // 4. التحديث
      $profile->update($validated);

      // 5. الرد باستخدام التريت والريسورس
      return $this->success(
          new ProfileResource($profile->load('user')), 
          'Profile updated successfully'
      );
  }
}
