<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\UserChallenge;

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
}