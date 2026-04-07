<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    // Bắt đầu thử thách
    public function start(Challenge $challenge)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập để bắt đầu thử thách');
        }

        $user = Auth::user();

        // Kiểm tra nếu đã bắt đầu
        $progress = ChallengeProgress::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->first();

        if (!$progress) {
            // Tạo mới progress
            $progress = ChallengeProgress::create([
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'progress' => 0,
                'started_at' => now()
            ]);
        }

        return redirect()->route('challenge.progress', $challenge->id)
            ->with('success', '🎉 Bắt đầu thử thách thành công!');
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

        return view('shop.challenge-progress', compact('challenge', 'category', 'progress'));
    }
}
