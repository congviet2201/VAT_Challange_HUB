<?php
/**
 * Mục đích file: app/Http/Controllers/ChallengeController.php
 * Xử lý các nghiệp vụ liên quan đến Thử thách (Challenge) của người dùng.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Challenge;
use App\Models\ChallengeAiPlan;
use App\Models\ChallengeAiTask;
use App\Models\ChallengeProgress;
use App\Models\Checkin;
use App\Services\ChallengeAiPlannerService;
use App\Services\ChallengeFeedbackService;

/**
 * Lớp ChallengeController: Điều phối toàn bộ các tương tác của người dùng với một Thử thách (Challenge).
 * Bao gồm các tác vụ:
 * - Bắt đầu tham gia thử thách
 * - Điểm danh (check-in) hàng ngày để duy trì chuỗi (streak)
 * - Yêu cầu AI tạo lộ trình cá nhân hóa dựa trên trình độ hiện tại
 * - Nộp minh chứng để hoàn thành các nhiệm vụ (tasks) do AI đề ra và cập nhật tiến độ
 *
 * Phụ thuộc chính:
 * - Models: Challenge, ChallengeProgress, Checkin, ChallengeAiPlan, ChallengeAiTask
 * - Services: ChallengeAiPlannerService (Gọi AI tạo lộ trình), ChallengeFeedbackService (Phân tích phản hồi)
 */
class ChallengeController extends Controller
{
    /**
     * Hàm checkin(): Xử lý logic điểm danh hàng ngày của người dùng cho một thử thách cụ thể.
     * Kiểm tra xem người dùng đã điểm danh trong ngày chưa, nếu chưa thì tạo bản ghi Checkin,
     * sau đó tính toán lại phần trăm hoàn thành và chuỗi ngày liên tục (streak).
     */
    public function checkin(Request $request)
    {
        $userId = Auth::id();
        $challengeId = $request->challenge_id;
        $today = now()->toDateString();

        // 1. Kiểm tra check-in hôm nay chưa
        $exists = Checkin::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã check-in hôm nay rồi!');
        }

        // 2. Tạo bản ghi Checkin
        Checkin::create([
            'user_id' => $userId,
            'challenge_id' => $challengeId,
            'date' => $today,
            'status' => 'done'
        ]);

        // 3. Cập nhật tiến trình (Dùng Model ChallengeProgress cho đồng bộ)
        $uc = ChallengeProgress::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->first();

        if ($uc) {
            $uc->completed_days += 1;

            // Giả sử mỗi thử thách mặc định 30 ngày
            $totalDays = $uc->challenge->duration_days ?? 30;
            $uc->progress = min(($uc->completed_days / $totalDays) * 100, 100);

            // 4. Tính streak (chuỗi ngày liên tiếp)
            $yesterday = now()->subDay()->toDateString();
            $checkedYesterday = Checkin::where('user_id', $userId)
                ->where('challenge_id', $challengeId)
                ->where('date', $yesterday)
                ->exists();

            if ($checkedYesterday) {
                $uc->streak += 1;
            } else {
                $uc->streak = 1;
            }

            $uc->save();
        }

        return back()->with('success', 'Check-in thành công!');
    }

    /**
     * Hàm start(): Xử lý logic khi người dùng bấm nút "Bắt đầu thử thách".
     * Khởi tạo một bản ghi tiến độ (ChallengeProgress) với điểm xuất phát ban đầu là 0
     * nếu người dùng chưa từng tham gia thử thách này.
     */
    public function start(Challenge $challenge)
    {
        $user = Auth::user();

        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if (!$progress) {
            $progress = ChallengeProgress::create([
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'progress' => 0,
                'completed_days' => 0,
                'streak' => 0,
                'started_at' => now()
            ]);
        }

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', 'Bắt đầu thử thách thành công!');
    }

    /**
     * Hàm progress(): Hiển thị trang theo dõi tiến độ của thử thách.
     * Cung cấp các thông tin: tổng quan tiến độ, lộ trình do AI lập ra (nếu có),
     * số lượng task đã hoàn thành và xem hôm nay đã hoàn thành task nào chưa.
     */
    public function progress(Challenge $challenge)
    {
        $user = Auth::user();
        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->firstOrFail();

        $category = $challenge->category;
        $feedback = ChallengeFeedbackService::getFeedback($progress, $challenge);
        $latestAiPlan = ChallengeAiPlan::with('tasks')
            ->where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->latest()
            ->first();
        $this->syncProgressFromAiPlan($progress, $latestAiPlan);
        $progress->refresh();
        $aiTaskCount = $latestAiPlan ? $latestAiPlan->tasks->count() : 0;
        $completedAiTaskCount = $latestAiPlan
            ? $latestAiPlan->tasks->whereNotNull('completed_at')->count()
            : 0;
        $hasCompletedAiTaskToday = ChallengeAiTask::query()
            ->whereHas('plan', function ($query) use ($user, $challenge) {
                $query->where('user_id', $user->id)
                    ->where('challenge_id', $challenge->id);
            })
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', now()->toDateString())
            ->exists();

        return view('shop.challenge-progress', compact(
            'challenge',
            'category',
            'progress',
            'feedback',
            'latestAiPlan',
            'aiTaskCount',
            'completedAiTaskCount',
            'hasCompletedAiTaskToday'
        ));
    }

    /**
     * Hàm generateAiRoadmap(): Nhận yêu cầu từ người dùng để sinh ra lộ trình học tập bằng AI.
     * Cần đầu vào là trình độ hiện tại của người dùng. Hệ thống sẽ gọi AI tạo ra danh sách các nhiệm vụ cụ thể.
     */
    public function generateAiRoadmap(Request $request, Challenge $challenge)
    {
        $request->validate([
            'current_level' => 'required|string|min:5|max:1000',
        ], [
            'current_level.required' => 'Vui lòng nhập trình độ hiện tại để AI cá nhân hóa lộ trình.',
        ]);

        $user = Auth::user();

        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if (!$progress) {
            return redirect()->route('challenge.detail', $challenge->id)
                ->with('error', 'Bạn cần tham gia thử thách trước khi tạo lộ trình AI.');
        }

        $plan = ChallengeAiPlannerService::generatePlan(
            $challenge,
            $user->id,
            trim((string) $request->input('current_level'))
        );
        $this->syncProgressFromAiPlan($progress, $plan);

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', 'Đã tạo lộ trình AI cá nhân hóa cho bạn.');
    }

    /**
     * Hàm completeAiTask(): Xử lý khi người dùng nộp minh chứng hoàn thành một nhiệm vụ do AI đề ra.
     * Yêu cầu phải đính kèm hình ảnh làm minh chứng. Lưu ảnh vào hệ thống, đánh dấu task đã hoàn thành,
     * và đồng thời cập nhật lại tiến độ (phần trăm) của toàn bộ thử thách.
     */
    public function completeAiTask(Request $request, Challenge $challenge, ChallengeAiTask $task)
    {
        $request->validate([
            'proof_image' => 'required|image|max:5120',
        ], [
            'proof_image.required' => 'Vui lòng tải ảnh minh chứng trước khi hoàn thành task.',
            'proof_image.image' => 'File minh chứng phải là hình ảnh.',
            'proof_image.max' => 'Ảnh minh chứng không được vượt quá 5MB.',
        ]);

        $user = Auth::user();
        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if (!$progress) {
            return redirect()->route('challenge.detail', $challenge->id)
                ->with('error', 'Bạn cần bấm bắt đầu thử thách trước khi hoàn thành task.');
        }

        $task->loadMissing('plan');

        if (
            !$task->plan ||
            $task->plan->user_id !== $user->id ||
            $task->plan->challenge_id !== $challenge->id
        ) {
            abort(403);
        }

        if ($task->completed_at) {
            return redirect()->route('challenge.progress', $challenge->id)
                ->with('error', 'Task này đã được hoàn thành trước đó.');
        }

        $hasCompletedAiTaskToday = ChallengeAiTask::query()
            ->whereHas('plan', function ($query) use ($user, $challenge) {
                $query->where('user_id', $user->id)
                    ->where('challenge_id', $challenge->id);
            })
            ->whereNotNull('completed_at')
            ->whereDate('completed_at', now()->toDateString())
            ->exists();

        if ($hasCompletedAiTaskToday) {
            return redirect()->route('challenge.progress', $challenge->id)
                ->with('error', 'Bạn đã hoàn thành 1 task hôm nay. Vui lòng quay lại vào ngày mai.');
        }

        $path = $request->file('proof_image')->store('challenge-proofs', 'public');

        $task->update([
            'completed_at' => now(),
            'proof_image_path' => $path,
        ]);

        $latestAiPlan = ChallengeAiPlan::with('tasks')
            ->where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->latest()
            ->first();

        $this->syncProgressFromAiPlan($progress, $latestAiPlan);

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', 'Đã hoàn thành task và cập nhật tiến độ.');
    }

    /**
     * Hàm createTasksForChallenge(): Tạo danh sách các nhiệm vụ mặc định ban đầu nếu thử thách chưa có nhiệm vụ nào.
     * Dùng như một biện pháp dự phòng (fallback) để đảm bảo luôn có sườn nội dung cơ bản.
     */
    protected function createTasksForChallenge(Challenge $challenge): void
    {
        if ($challenge->tasks()->exists()) {
            return;
        }

        $baseTasks = [
            [
                'order' => 1,
                'title' => 'Xác định mục tiêu phiên đầu tiên',
                'description' => 'Đọc mô tả challenge và xác định kết quả nhỏ bạn muốn đạt được trong hôm nay.',
            ],
            [
                'order' => 2,
                'title' => 'Thực hiện phiên chính',
                'description' => 'Dành đúng thời gian khuyến nghị của challenge để làm phần việc quan trọng nhất.',
            ],
            [
                'order' => 3,
                'title' => 'Tự đánh giá nhanh',
                'description' => 'Ghi lại điều đã hoàn thành, điểm còn vướng và bước tiếp theo cho ngày mai.',
            ],
        ];

        foreach ($baseTasks as $task) {
            $challenge->tasks()->create($task);
        }
    }

    /**
     * Hàm syncProgressFromAiPlan(): Đồng bộ (tính toán lại) phần trăm tiến độ của thử thách
     * dựa trên số lượng các nhiệm vụ AI (AI Tasks) mà người dùng đã hoàn thành so với tổng số nhiệm vụ.
     */
    protected function syncProgressFromAiPlan(ChallengeProgress $progress, ?ChallengeAiPlan $plan): void
    {
        if (!$plan) {
            $progress->update([
                'progress' => 0,
                'completed_days' => 0,
                'completed_at' => null,
            ]);

            return;
        }

        $tasks = $plan->relationLoaded('tasks') ? $plan->tasks : $plan->tasks()->get();
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->whereNotNull('completed_at')->count();
        $progressValue = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        $progress->update([
            'progress' => min(100, max(0, $progressValue)),
            'completed_days' => $completedTasks,
            'completed_at' => $completedTasks > 0 && $completedTasks === $totalTasks ? now() : null,
        ]);
    }
}
