<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AuthController;
use App\Models\UserChallenge;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

Route::get('/dashboard', function () {
    $userChallenges = UserChallenge::where('user_id', auth()->id())->get();
    return view('dashboard', compact('userChallenges'));
})->middleware('auth');