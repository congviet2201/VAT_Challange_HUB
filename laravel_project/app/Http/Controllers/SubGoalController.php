<?php
/**
 * Mục đích file: app/Http/Controllers/SubGoalController.php
 * Xử lý các thao tác hoàn thành nhiệm vụ phụ (Sub-goal) và lưu minh chứng.
 */

namespace App\Http\Controllers;

use App\Models\SubGoal;
use App\Models\SubGoalProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Lớp SubGoalController: Chịu trách nhiệm xử lý các tác vụ xoay quanh Mục tiêu phụ (SubGoal).
 * Cụ thể là nhận minh chứng (bằng văn bản hoặc hình ảnh) do người dùng nộp lên, kiểm tra logic ràng buộc,
 * đánh dấu hoàn thành cho mục tiêu phụ này và tự động đồng bộ tiến độ với Mục tiêu lớn (Goal).
 *
 * Phụ thuộc chính:
 * - Models: SubGoal, SubGoalProof
 * - Auth: để ràng buộc quyền sở hữu dữ liệu theo người dùng đang đăng nhập
 */
class SubGoalController extends Controller
{
    /**
     * Hàm submitProof(): Xử lý việc nộp minh chứng cho một mục tiêu phụ cụ thể.
     * Kiểm tra trạng thái mục tiêu phụ (nếu đã hoàn thành thì không cho nộp nữa) và lưu nội dung minh chứng vào CSDL.
     */
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

    /**
     * Hàm complete(): Xử lý xác nhận hoàn thành mục tiêu phụ và đồng bộ trạng thái lên mục tiêu cha.
     * Ràng buộc: Mỗi mục tiêu lớn chỉ được hoàn thành tối đa 1 mục tiêu phụ trong một ngày. Yêu cầu đã nộp minh chứng trước đó.
     */
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
