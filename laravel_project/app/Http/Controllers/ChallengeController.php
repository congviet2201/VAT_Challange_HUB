<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeFeedbackHistory;
use App\Models\ChallengeProgress;
use App\Models\Checkin;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\UserChallenge;
use App\Services\ChallengeFeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChallengeController extends Controller
{
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
                'started_at' => now(),
            ]);
        }

        $this->createTasksForChallenge($challenge);

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', 'Bắt đầu thử thách thành công!');
    }

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
        $allTasks = collect();
        $completedTaskIds = [];

        if ($this->taskTablesReady()) {
            $allTasks = $challenge->tasks()->orderBy('order')->get();

            if ($allTasks->isNotEmpty()) {
                $completedTaskIds = TaskCompletion::where('user_id', $user->id)
                    ->whereIn('task_id', $allTasks->pluck('id'))
                    ->pluck('task_id')
                    ->all();
            }
        }

        $feedback = ChallengeFeedbackService::getFeedback($progress, $challenge);

        return view('shop.challenge-progress', compact(
            'challenge',
            'category',
            'progress',
            'allTasks',
            'completedTaskIds',
            'feedback'
        ));
    }

    public function completeTask(Request $request, Challenge $challenge, Task $task)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        if (!$this->taskTablesReady()) {
            return response()->json([
                'error' => 'Tính năng các bước chưa sẵn sàng. Vui lòng chạy migrate cơ sở dữ liệu.',
            ], 503);
        }

        $user = Auth::user();

        if ($task->challenge_id !== $challenge->id) {
            return response()->json(['error' => 'Bước thử thách không hợp lệ'], 400);
        }

        $lastCompletion = TaskCompletion::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->first();

        if ($lastCompletion && $lastCompletion->created_at->addSeconds(10) > now()) {
            $remainingSeconds = (int) $lastCompletion->created_at->addSeconds(10)->diffInSeconds(now());

            return response()->json([
                'error' => "Vui lòng chờ {$remainingSeconds} giây trước khi hoàn thành bước tiếp theo",
            ], 429);
        }

        $completion = TaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        if ($completion) {
            return response()->json(['error' => 'Bước này đã được hoàn thành rồi'], 400);
        }

        TaskCompletion::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'completed_at' => now(),
        ]);

        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if (!$progress) {
            return response()->json(['error' => 'Không tìm thấy tiến độ thử thách'], 404);
        }

        $challengeTaskIds = $challenge->tasks()->pluck('id');
        $taskCount = max(1, $challengeTaskIds->count());
        $completedCount = TaskCompletion::where('user_id', $user->id)
            ->whereIn('task_id', $challengeTaskIds)
            ->count();

        $newProgress = (int) round(($completedCount / $taskCount) * 100);
        $progress->progress = $newProgress;

        if ($newProgress >= 100 && empty($progress->completed_at)) {
            $progress->completed_at = now();
        }

        $progress->save();

        return response()->json([
            'success' => true,
            'message' => 'Bạn vừa hoàn thành một bước rất tốt!',
            'progress' => $progress->progress,
        ]);
    }

    public function checkin(Request $request)
    {
        $userId = Auth::id();
        $challengeId = $request->challenge_id;
        $today = now()->toDateString();

        $lastCheckin = Checkin::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        if ($lastCheckin && $lastCheckin->created_at->addSeconds(10) > now()) {
            $remainingSeconds = (int) $lastCheckin->created_at->addSeconds(10)->diffInSeconds(now());

            return back()->with('error', "Vui lòng chờ {$remainingSeconds} giây trước khi check-in tiếp theo");
        }

        $exists = Checkin::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã check-in hôm nay rồi!');
        }

        Checkin::create([
            'user_id' => $userId,
            'challenge_id' => $challengeId,
            'date' => $today,
            'status' => 'done',
        ]);

        $progress = ChallengeProgress::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->first();

        if ($progress) {
            $progress->completed_days += 1;

            $totalDays = $progress->challenge->duration_days ?? 30;
            $progress->progress = min(($progress->completed_days / $totalDays) * 100, 100);

            $yesterday = now()->subDay()->toDateString();
            $checkedYesterday = Checkin::where('user_id', $userId)
                ->where('challenge_id', $challengeId)
                ->where('date', $yesterday)
                ->exists();

            $progress->streak = $checkedYesterday ? $progress->streak + 1 : 1;

            if ($progress->progress >= 100 && empty($progress->completed_at)) {
                $progress->completed_at = now();
            }

            $progress->save();
        }

        return back()->with('success', 'Check-in thành công!');
    }

    /**
     * Reset toàn bộ tiến độ thử thách của tất cả người dùng về 0.
     * Chỉ dành cho Admin.
     */
    public function resetAllProgress()
    {
        DB::transaction(function () {
            // Tắt kiểm tra khóa ngoại để có thể làm sạch bảng hoàn toàn
            Schema::disableForeignKeyConstraints();

            // 1. Dọn sạch các bảng liên quan đến tiến độ (Reset ID về 1)
            ChallengeProgress::truncate();
            UserChallenge::truncate();
            Checkin::truncate();
            TaskCompletion::truncate();
            ChallengeFeedbackHistory::truncate();

            // 2. Xóa liên kết người dùng vào các nhóm (nếu có) để về trạng thái mới tinh
            DB::table('group_user')->truncate();

            // 3. Xóa các bảng nhật ký bổ sung nếu tồn tại trong DB
            if (Schema::hasTable('streak_logs')) DB::table('streak_logs')->delete();
            if (Schema::hasTable('ai_logs')) DB::table('ai_logs')->delete();
            if (Schema::hasTable('sessions')) DB::table('sessions')->whereNotNull('user_id')->delete();

            Schema::enableForeignKeyConstraints();
        });

        return back()->with('success', '✅ Hệ thống đã được reset hoàn toàn! Mọi người dùng hiện đã quay về trạng thái như lúc mới đăng ký.');
    }

    private function createTasksForChallenge(Challenge $challenge): void
    {
        if (!$this->taskTablesReady()) {
            return;
        }

        if ($challenge->tasks()->count() > 0) {
            return;
        }

        $taskTitles = [
            1 => 'Bước 1: Học lý thuyết',
            2 => 'Bước 2: Thực hành viết',
            3 => 'Bước 3: Bài tập áp dụng',
            4 => 'Bước 4: Kiểm tra hiểu biết',
            5 => 'Bước 5: Tóm tắt ghi chép',
            6 => 'Bước 6: Ôn tập lại',
            7 => 'Bước 7: Tìm hiểu sâu',
            8 => 'Bước 8: Thách thức cao hơn',
            9 => 'Bước 9: Kiểm tra cuối cùng',
            10 => 'Bước 10: Hoàn thành và tổng kết',
        ];

        $taskDescriptions = [
            1 => 'Đọc và hiểu các khái niệm chính',
            2 => 'Làm bài tập luyện tập cơ bản',
            3 => 'Áp dụng kiến thức vào thực tế',
            4 => 'Kiểm tra lại những gì đã học',
            5 => 'Tóm tắt bài học và ghi chép',
            6 => 'Ôn tập lại các nội dung quan trọng',
            7 => 'Đi sâu vào chi tiết thêm',
            8 => 'Thử thách bản thân với bài tập khó',
            9 => 'Làm bài kiểm tra cuối cùng',
            10 => 'Tổng hợp lại toàn bộ bài học',
        ];

        foreach ($taskTitles as $order => $title) {
            Task::create([
                'challenge_id' => $challenge->id,
                'order' => $order,
                'title' => $title,
                'description' => $taskDescriptions[$order],
            ]);
        }
    }

    private function taskTablesReady(): bool
    {
        return Schema::hasTable('tasks') && Schema::hasTable('task_completions');
    }
}
