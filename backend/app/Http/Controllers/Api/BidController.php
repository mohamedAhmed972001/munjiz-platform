<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;

class BidController extends Controller
{
    // تقديم عرض
    public function store(Request $request) {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:1',
            'message' => 'required|string',
        ]);

        $bid = $request->user()->bids()->create($validated);
        return response()->json($bid, 201);
    }

    // جلب عروض مشروع معين (للكلاينت صاحب المشروع)
    public function getProjectBids($projectId) {
        $project = Project::findOrFail($projectId);
        if (auth()->id() !== $project->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $bids = Bid::where('project_id', $projectId)->with('user')->latest()->get();
        return response()->json($bids);
    }
}