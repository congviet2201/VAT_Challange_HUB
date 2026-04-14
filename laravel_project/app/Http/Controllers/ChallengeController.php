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
        try {
            $userId = Auth::id();
            $challengeId = $request->challenge_id;
            $today = now()->toDateString();

            // Validate input
            if (!$challengeId) {
                return back()->with('error', 'Thiếu thông tin thử thách!');
            }

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

            // 3. Cập nhật tiến trình
            /** @var ChallengeProgress $progress */
            $progress = ChallengeProgress::where('user_id', $userId)
                ->where('challenge_id', $challengeId)
                ->first();

            if ($progress) {
                $progress->completed_days += 1;

                // Giả sử mỗi thử thách mặc định 30 ngày
                $totalDays = $progress->challenge->duration_days ?? 30;
                $progress->progress = min(($progress->completed_days / $totalDays) * 100, 100);

                // 4. Tính streak (chuỗi ngày liên tiếp)
                $yesterday = now()->subDay()->toDateString();
                $checkedYesterday = Checkin::where('user_id', $userId)
                    ->where('challenge_id', $challengeId)
                    ->where('date', $yesterday)
                    ->exists();

                if ($checkedYesterday) {
                    $progress->streak += 1;
                } else {
                    $progress->streak = 1;
                }

                $progress->save();
            }

            return back()->with('success', 'Check-in thành công!');
        } catch (\Exception $e) {
            \Log::error('Checkin error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi check-in. Vui lòng thử lại!');
        }
    }

    // Bắt đầu thử thách
    public function start(Challenge $challenge)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập để tham gia thử thách!');
            }

            // Kiểm tra đã tham gia chưa
            $existingProgress = ChallengeProgress::where('user_id', $user->id)
                ->where('challenge_id', $challenge->id)
                ->first();

            if ($existingProgress) {
                return redirect()->route('challenge.progress', $challenge->id)
                    ->with('info', 'Bạn đã tham gia thử thách này rồi!');
            }

            // Tạo progress mới
            $progress = ChallengeProgress::create([
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'progress' => 0,
                'completed_days' => 0,
                'streak' => 0,
                'started_at' => now()
            ]);

            return redirect()->route('challenge.progress', $challenge->id)
                ->with('success', 'Bắt đầu thử thách thành công!');
        } catch (\Exception $e) {
            \Log::error('Error starting challenge: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tham gia thử thách. Vui lòng thử lại!');
        }
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

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $challenges = Challenge::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('title', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
            })
            ->latest()
            ->paginate(6);

        return view('shop.pages.challenges', compact('challenges', 'keyword'));
    }
}
