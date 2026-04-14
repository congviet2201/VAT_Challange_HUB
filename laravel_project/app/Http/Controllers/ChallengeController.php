<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\ChallengeProgress;
use App\Models\Checkin;

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

        return view('shop.challenge-progress', compact('challenge', 'category', 'progress'));
    }
}