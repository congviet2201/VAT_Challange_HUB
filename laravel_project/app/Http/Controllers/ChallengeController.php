<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Challenge;
use App\Models\ChallengeAiPlan;
use App\Models\ChallengeAiTask;
use App\Models\ChallengeProgress;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\Checkin;
use App\Services\ChallengeAiPlannerService;
use App\Services\ChallengeFeedbackService;

class ChallengeController extends Controller
{
    /**
     * Handle daily checkin for a challenge.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkin(Request $request)
    {
        // Lấy ID người dùng đã đăng nhập
        $userId = Auth::id();
        $challengeId = $request->challenge_id;
        $today = now()->toDateString();

        // 1. Kiểm tra xem người dùng đã check-in hôm nay chưa
        $exists = Checkin::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã check-in hôm nay rồi!');
        }

        // 2. Tạo bản ghi checkin mới
        Checkin::create([
            'user_id' => $userId,
            'challenge_id' => $challengeId,
            'date' => $today,
            'status' => 'done'
        ]);

        // 3. Cập nhật tiến trình thử thách của người dùng
        $uc = ChallengeProgress::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->first();

        if ($uc) {
            $uc->completed_days += 1;

            // Giả sử thử thách kéo dài 30 ngày nếu không có giá trị duration_days
            $totalDays = $uc->challenge->duration_days ?? 30;
            $uc->progress = min(($uc->completed_days / $totalDays) * 100, 100);

            // 4. Tính streak (số ngày liên tiếp)
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
     * Start a challenge for the authenticated user.
     *
     * @param Challenge $challenge
     * @return \Illuminate\Http\RedirectResponse
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

            // Tạo tasks cho challenge này nếu chưa có
            $this->createTasksForChallenge($challenge);
        }

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', 'Bắt đầu thử thách thành công!');
    }

    /**
     * Show progress details for a challenge.
     *
     * @param Challenge $challenge
     * @return \Illuminate\View\View
     */
    public function progress(Challenge $challenge)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

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

        return view('shop.challenge-progress', compact(
            'challenge',
            'category',
            'progress',
            'feedback',
            'latestAiPlan',
            'aiTaskCount',
            'completedAiTaskCount'
        ));
    }

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
