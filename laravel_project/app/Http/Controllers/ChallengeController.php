<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\ChallengeProgress;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\Checkin;

class ChallengeController extends Controller
{
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

            // Tạo tasks cho challenge này nếu chưa có
            $this->createTasksForChallenge($challenge);
        }

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', '🎉 Bắt đầu thử thách thành công!');
    }

    // Tạo tasks cho challenge
    private function createTasksForChallenge(Challenge $challenge)
    {
        // Kiểm tra xem đã có tasks chưa
        if ($challenge->tasks()->count() > 0) {
            return;
        }

        // Mỗi challenge có 10 tasks
        $taskCount = 10;

        $taskTitles = [
            1 => '📚 Bước 1: Học lý thuyết',
            2 => '✍️ Bước 2: Thực hành viết',
            3 => '🎯 Bước 3: Bài tập áp dụng',
            4 => '🔍 Bước 4: Kiểm tra hiểu biết',
            5 => '📝 Bước 5: Tóm tắt ghi chép',
            6 => '🎓 Bước 6: Ôn tập lại',
            7 => '💡 Bước 7: Tìm hiểu sâu',
            8 => '🚀 Bước 8: Thách thức cao hơn',
            9 => '✅ Bước 9: Kiểm tra cuối cùng',
            10 => '🏆 Bước 10: Hoàn thành và tổng kết'
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
            10 => 'Tổng hợp lại toàn bộ bài học'
        ];

        for ($i = 1; $i <= $taskCount; $i++) {
            Task::create([
                'challenge_id' => $challenge->id,
                'order' => $i,
                'title' => $taskTitles[$i],
                'description' => $taskDescriptions[$i]
            ]);
        }
    }

    // Trang tiến độ thử thách
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

        // Lấy tất cả tasks của challenge
        $allTasks = $challenge->tasks()->orderBy('order')->get();

        // Lấy tasks đã hoàn thành của user này
        $completedTaskIds = TaskCompletion::where('user_id', $user->id)
            ->whereIn('task_id', $allTasks->pluck('id'))
            ->pluck('task_id')
            ->toArray();

        // Tính tiến độ từ tasks
        if ($allTasks->count() > 0) {
            $progress->progress = round((\count($completedTaskIds) / $allTasks->count()) * 100);
            $progress->save();
        }

        return view('shop.challenge-progress', compact('challenge', 'category', 'progress', 'allTasks', 'completedTaskIds'));
    }

    // Hoàn thành task
    public function completeTask(Request $request, Challenge $challenge, Task $task)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $user = Auth::user();

        // Kiểm tra task có thuộc challenge này không
        if ($task->challenge_id !== $challenge->id) {
            return response()->json(['error' => 'Task không hợp lệ'], 400);
        }

    // Kiểm tra ràng buộc thời gian 10 giây
        //Tìm trong bảng TaskCompletion (bảng lưu lịch sử các task đã xong)
        // xem lần cuối cùng người dùng này nhấn "Hoàn thành" là khi nào.
        $lastCompletion = TaskCompletion::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCompletion && $lastCompletion->created_at->addSeconds(10) > now()) {
            // tính điểm hiện tại và mốc "được phép" để báo cho người dùng biết họ cần đợi thêm chính xác bao nhiêu giây nữa (diffInSeconds).
            $remainingSeconds = intval($lastCompletion->created_at->addSeconds(10)->diffInSeconds(now()));
            return response()->json([
                'error' => "Vui lòng chờ {$remainingSeconds} giây trước khi hoàn thành task tiếp theo"
            ], 429);
        }

        // Kiểm tra xem user đã hoàn thành task này chưa
        $completion = TaskCompletion::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        if (!$completion) {
            // Tạo bản ghi hoàn thành task
            TaskCompletion::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'completed_at' => now()
            ]);

            // Cập nhật progress
            // tìm trong bảng Chall user_id đã đang thực hiện call_id nào
            $progress = ChallengeProgress::where('user_id', $user->id)
                ->where('challenge_id', $challenge->id)
                ->first();
            // nếu tìm thấy lấy tất cả tasks của challenge này
            if ($progress) {
                $allTasks = $challenge->tasks()->count();
            // chỉ lấy task đã hoàn thành
                $completedCount = TaskCompletion::where('user_id', $user->id)
                    ->whereIn('task_id', $challenge->tasks()->pluck('id'))
                    ->count();

                $newProgress = round(($completedCount / $allTasks) * 100);
                $progress->progress = $newProgress;

                // Hoàn thành challenge nếu đạt 100%
                if ($newProgress >= 100) {
                    $progress->completed_at = now();
                }

                $progress->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Tuyệt vời! Task hoàn thành!',
                'progress' => $progress->progress
            ]);
        }

        return response()->json(['error' => 'Task đã hoàn thành rồi'], 400);
    }

    public function checkin(Request $request)
    {
        // Sửa lỗi đỏ ở auth()->id() bằng cách dùng Auth::id()
        $userId = Auth::id();
        $challengeId = $request->challenge_id;
        $today = now()->toDateString();

        // 1. Kiểm tra ràng buộc thời gian 10 giây
        $lastCheckin = Checkin::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCheckin && $lastCheckin->created_at->addSeconds(10) > now()) {
            $remainingSeconds = intval($lastCheckin->created_at->addSeconds(10)->diffInSeconds(now()));
            return back()->with('error', "Vui lòng chờ {$remainingSeconds} giây trước khi check-in tiếp theo");
        }

        // 2. Kiểm tra check-in hôm nay chưa
        $exists = Checkin::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã check-in hôm nay rồi!');
        }

        // 3. Tạo bản ghi Checkin
        Checkin::create([
            'user_id' => $userId,
            'challenge_id' => $challengeId,
            'date' => $today,
            'status' => 'done'
        ]);

        // 4. Cập nhật tiến trình (Dùng Model ChallengeProgress cho đồng bộ)
        $uc = ChallengeProgress::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->first();

        if ($uc) {
            $uc->completed_days += 1;

            // Giả sử mỗi thử thách mặc định 30 ngày
            $totalDays = $uc->challenge->duration_days ?? 30;
            $uc->progress = min(($uc->completed_days / $totalDays) * 100, 100);


            $uc->save();
        }

        return back()->with('success', 'Check-in thành công!');
    }
}

