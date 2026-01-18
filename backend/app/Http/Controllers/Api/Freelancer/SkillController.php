<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Http\Resources\SkillResource; // استدعاء الريسورس
use App\Traits\ApiResponseTrait;     // استدعاء التريت عشان ميثود success
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. استدعاء الـ Trait الناقص
class SkillController extends Controller
{
  use ApiResponseTrait,AuthorizesRequests; // تفعيل التريت عشان نستخدم ميثود success

    /**
     * List freelancer skills
     */
    public function index(Request $request)
    {
        $profile = $request->user()->profile;

        // استخدام الـ Resource بيخلي الـ JSON شكله احترافي
        return $this->success(
            SkillResource::collection($profile->skills),
            'Skills retrieved successfully'
        );
    }

    /**
     * Attach skills to freelancer profile
     */
    public function attach(Request $request)
    {
        $validated = $request->validate([
            'skills'   => ['required', 'array'],
            'skills.*' => ['exists:skills,id'],
        ]);

        $profile = $request->user()->profile;
        $this->authorize('manage', $profile);
        // ميثود syncWithoutDetaching بتمنع تكرار الـ ID في الداتابيز
        $profile->skills()->syncWithoutDetaching($validated['skills']);

        return $this->success(
            SkillResource::collection($profile->load('skills')->skills),
            'Skills attached successfully'
        );
    }

    /**
     * Detach skill from freelancer profile
     */
    public function detach(Request $request, Skill $skill)
    {
        $profile = $request->user()->profile;
        $this->authorize('manage', $profile);
        $profile->skills()->detach($skill->id);

        // السطر 63 اللي كان بيطلع Error دلوقتي هيشتغل صح
        return $this->success(
            SkillResource::collection($profile->load('skills')->skills),
            'Skill detached successfully'
        );
    }
}