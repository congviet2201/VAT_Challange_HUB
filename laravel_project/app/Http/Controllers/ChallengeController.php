<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\ChallengeProgress;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\Checkin;
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

        return view('shop.challenge-progress', compact('challenge', 'category', 'progress', 'feedback'));
    }
}


