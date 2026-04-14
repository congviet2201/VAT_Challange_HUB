<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ChallengeController as AdminChallengeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\UserChallenge;




// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

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
    $userChallenges = UserChallenge::where('user_id', Auth::id())->get();
    return view('dashboard', compact('userChallenges'));
})->middleware('auth');






// Nhóm các Route dành cho Admin lại một chỗ cho gọn
Route::middleware(['auth', 'admin'])->group(function () {

    // Quản lý user
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

    // Quản lý thử thách
    Route::get('/admin/challenges', [AdminChallengeController::class, 'index'])->name('admin.challenges.index');
    Route::get('/admin/challenges/create', [AdminChallengeController::class, 'create'])->name('admin.challenges.create');
    Route::post('/admin/challenges', [AdminChallengeController::class, 'store'])->name('admin.challenges.store');
    Route::get('/admin/challenges/{challenge}', [AdminChallengeController::class, 'show'])->name('admin.challenges.show');
    Route::get('/admin/challenges/{challenge}/edit', [AdminChallengeController::class, 'edit'])->name('admin.challenges.edit');
    Route::put('/admin/challenges/{challenge}', [AdminChallengeController::class, 'update'])->name('admin.challenges.update');
    Route::delete('/admin/challenges/{challenge}', [AdminChallengeController::class, 'destroy'])->name('admin.challenges.destroy');

});
