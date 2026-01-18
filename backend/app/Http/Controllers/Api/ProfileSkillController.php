<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachSkillsRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\Skill;
use Illuminate\Http\Request;

class ProfileSkillController extends Controller
{
    public function __construct()
    {
        // Ensure session-based auth for attach/detach endpoints
        $this->middleware('auth:sanctum')->only(['attach', 'detach', 'mySkills']);
    }

    /**
     * POST /api/profile/me/skills
     * Body: { "skills": [1,2,3] }
     * Attach multiple skills to authenticated user's profile (no duplicates)
     */
    public function attach(AttachSkillsRequest $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (! $profile) {
            return response()->json(['status' => 'error', 'message' => 'Profile not found'], 404);
        }

        // Authorization: owner or admin via policy
        $this->authorize('update', $profile);

        $skillIds = $request->input('skills', []);
        // ensure all ids exist (AttachSkillsRequest already validated exists)
        // use syncWithoutDetaching to avoid duplicates
        $profile->skills()->syncWithoutDetaching($skillIds);

        $profile->load('skills');

        return new ProfileResource($profile);
    }

    /**
     * DELETE /api/profile/me/skills/{skill}
     * Detach a single skill from current user's profile
     */
    public function detach(Request $request, Skill $skill)
    {
        $user = $request->user();
        $profile = $user->profile;

        if (! $profile) {
            return response()->json(['status' => 'error', 'message' => 'Profile not found'], 404);
        }

        $this->authorize('update', $profile);

        $profile->skills()->detach($skill->id);

        $profile->load('skills');

        return new ProfileResource($profile);
    }

    /**
     * GET /api/profile/me/skills
     * Return skills of authenticated user's profile
     */
    public function mySkills(Request $request)
    {
        $profile = $request->user()->profile;

        if (! $profile) {
            return response()->json(['status' => 'error', 'message' => 'Profile not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profile->skills->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'slug'=>$s->slug]),
        ]);
    }
}
