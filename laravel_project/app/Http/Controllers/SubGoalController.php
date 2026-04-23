<?php

namespace App\Http\Controllers;

use App\Models\SubGoal;
use App\Models\SubGoalProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubGoalController extends Controller
{
    public function submitProof(Request $request, int $id)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'type' => 'required|in:image,text',
            'content' => 'required|string',
        ]);

        $subGoal = SubGoal::query()
            ->ownedByUser($userId)
            ->where('id', $id)
            ->firstOrFail();

        if ($subGoal->status === 'completed') {
            return response()->json([
                'message' => 'Nhiệm vụ phụ này đã được hoàn thành.',
            ], 422);
        }

        $proof = SubGoalProof::create([
            'sub_goal_id' => $subGoal->id,
            'type' => $validated['type'],
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Proof submitted successfully.',
            'proof' => $proof,
        ], 201);
    }

    public function complete(Request $request, int $id)
    {
        $userId = Auth::id();

        $subGoal = SubGoal::query()
            ->ownedByUser($userId)
            ->where('id', $id)
            ->with('goal')
            ->firstOrFail();

        if ($subGoal->status === 'completed') {
            return response()->json([
                'message' => 'Nhiệm vụ phụ này đã được hoàn thành.',
            ], 422);
        }

        if (! $subGoal->hasProof()) {
            return response()->json([
                'message' => 'Proof is required before completing this sub-goal.',
            ], 422);
        }

        $goal = $subGoal->goal;

        // Mỗi goal chỉ được hoàn thành tối đa 1 sub-goal mỗi ngày.
        if (SubGoal::hasCompletedForGoalToday($goal->id)) {
            return response()->json([
                'message' => 'Bạn đã hoàn thành 1 nhiệm vụ phụ cho mục tiêu này hôm nay. Vui lòng quay lại vào ngày mai.',
            ], 422);
        }

        $goal = $subGoal->completeAndSyncGoal();
        $subGoal->refresh();

        return response()->json([
            'message' => 'Sub-goal completed successfully.',
            'sub_goal' => [
                'id' => $subGoal->id,
                'status' => $subGoal->status,
            ],
            'goal' => [
                'id' => $goal->id,
                'status' => $goal->status,
                'completed_sub_goals' => $goal->subGoals()->where('status', 'completed')->count(),
                'total_sub_goals' => $goal->subGoals()->count(),
            ],
        ]);
    }
}
