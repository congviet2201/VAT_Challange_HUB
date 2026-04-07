<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AuthController;
use App\Models\UserChallenge;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category');
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

Route::get('/dashboard', function () {
    $userChallenges = UserChallenge::where('user_id', auth()->id())->get();
    return view('dashboard', compact('userChallenges'));
})->middleware('auth');