<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * List freelancer skills
     */
    public function index(Request $request)
    {
        $profile = $request->user()->profile;

        return response()->json([
            'status' => true,
            'data' => $profile->skills,
        ]);
    }

    /**
     * Attach skills to freelancer profile
     */
    public function attach(Request $request)
    {
        $validated = $request->validate([
            'skills' => ['required', 'array'],
            'skills.*' => ['exists:skills,id'],
        ]);

        $profile = $request->user()->profile;

        // يمنع التكرار
        $profile->skills()->syncWithoutDetaching($validated['skills']);

        return response()->json([
            'status'  => true,
            'message' => 'Skills attached successfully',
        ]);
    }

    /**
     * Detach skill from freelancer profile
     */
    public function detach(Request $request, Skill $skill)
    {
        $profile = $request->user()->profile;

        $profile->skills()->detach($skill->id);

        return response()->json([
            'status'  => true,
            'message' => 'Skill detached successfully',
        ]);
    }
}
