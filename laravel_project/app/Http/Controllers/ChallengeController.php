<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\ChallengeProgress;
use App\Models\Checkin;
use App\Models\Task;
use App\Models\TaskCompletion;

class ChallengeController extends Controller
{
    public function checkin(Request $request)
    {
        // Sửa lỗi đỏ ở auth()->id() bằng cách dùng Auth::id()
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

    // Bắt đầu thử thách
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
            ->with('success', '🎉 Bắt đầu thử thách thành công!');
    }

    // Trang tiến độ thử thách
    public function progress(Challenge $challenge)
    {
        $user = Auth::user();

        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->firstOrFail();

        $category = $challenge->category;

        // Lấy 10 thử thách khác cho demo
        $demoChallenges = Challenge::where('id', '!=', $challenge->id)
            ->limit(10)
            ->get();

        // Lấy danh sách tasks cho challenge này
        $tasks = $challenge->tasks()
            ->with(['completions' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->get();

        // Tính phần trăm hoàn thành task
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->sum(function($task) {
            return $task->completions->count() > 0 ? 1 : 0;
        });

        return view('shop.challenge-progress', compact('challenge', 'category', 'progress', 'demoChallenges', 'tasks', 'totalTasks', 'completedTasks'));
    }

    // Toggle task completion
    public function toggleTask(Request $request)
    {
        $user = Auth::user();
        $taskId = $request->input('task_id');
        $challengeId = $request->input('challenge_id');

        $task = Task::findOrFail($taskId);
        $challenge = Challenge::findOrFail($challengeId);

        // Kiểm tra xem task đã hoàn thành chưa
        $completion = TaskCompletion::where('user_id', $user->id)
            ->where('task_id', $taskId)
            ->first();

        if ($completion) {
            // Nếu đã hoàn thành thì xóa
            $completion->delete();
            $isCompleted = false;
        } else {
            // Nếu chưa thì tạo mới
            TaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => $taskId,
                'challenge_id' => $challengeId,
                'completed_at' => now(),
            ]);
            $isCompleted = true;
        }

        // Tính lại progress
        $tasks = $challenge->tasks()
            ->with(['completions' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->get();

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->sum(function($t) {
            return $t->completions->count() > 0 ? 1 : 0;
        });

        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Cập nhật ChallengeProgress
        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challengeId)
            ->first();

        if ($progress) {
            $progress->update(['progress' => $progressPercent]);
        }

        return response()->json([
            'success' => true,
            'is_completed' => $isCompleted,
            'completed_tasks' => $completedTasks,
            'total_tasks' => $totalTasks,
            'progress_percent' => $progressPercent,
        ]);
    }
}
