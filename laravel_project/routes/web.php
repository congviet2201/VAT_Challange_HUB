<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChallengeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\UserChallenge;
use App\Http\Controllers\UserController;




// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Trang tất cả thử thách với tìm kiếm
Route::get('/challenges', [HomeController::class, 'challenges'])->name('challenges');

// Trang danh mục
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');

// Chi tiết thử thách (xem thông tin)
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');

// Bắt đầu thử thách (tạo progress và chuyển sang trang progress)
Route::post('/challenge/{challenge}/start', [ChallengeController::class, 'start'])->name('challenge.start')->middleware('auth');

// Trang tiến độ thử thách
Route::get('/challenge/{challenge}/progress', [ChallengeController::class, 'progress'])->name('challenge.progress')->middleware('auth');

// Trang Giới thiệu & Liên hệ
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

Route::get('/dashboard', function () {
    $userChallenges = \App\Models\ChallengeProgress::where('user_id', Auth::id())->with('challenge')->get();
    return view('dashboard', compact('userChallenges'));
})->middleware('auth');






// Test Checkin model
Route::get('/test-checkin', function () {
    try {
        $checkins = \App\Models\Checkin::all();
        return 'Checkins count: ' . $checkins->count();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->middleware('auth');

// Test checkin method
Route::post('/test-checkin-method', function (\Illuminate\Http\Request $request) {
    try {
        $controller = new \App\Http\Controllers\ChallengeController();
        return $controller->checkin($request);
    } catch (\Exception $e) {
        return 'Error in checkin method: ' . $e->getMessage();
    }
})->middleware('auth');
