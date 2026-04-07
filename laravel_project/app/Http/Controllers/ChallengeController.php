<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Challenge;
use App\Models\ChallengeProgress;

class ChallengeController extends Controller
{
public function checkin(Request $request)
{
$userId = auth()->id();
$challengeId = $request->challenge_id;

$today = now()->toDateString();

// không cho check-in 2 lần/ngày
$exists = Checkin::where('user_id', $userId)
->where('challenge_id', $challengeId)
->where('date', $today)
->exists();

if ($exists) {
return back()->with('error', 'Bạn đã check-in hôm nay rồi!');
}

// lưu checkin
Checkin::create([
'user_id' => $userId,
'challenge_id' => $challengeId,
'date' => $today,
'status' => 'done'
]);

// cập nhật tiến trình
$uc = UserChallenge::where('user_id', $userId)
->where('challenge_id', $challengeId)
->first();

$uc->completed_days += 1;

// giả sử challenge 30 ngày
$uc->progress = ($uc->completed_days / 30) * 100;

// tính streak
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

return back()->with('success', 'Check-in thành công!');
}





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
