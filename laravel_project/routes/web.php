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

// Trang danh mục
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');

// Chi tiết thử thách (xem thông tin)
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');

// Bắt đầu thử thách (tạo progress và chuyển sang trang progress)
Route::post('/challenge/{challenge}/start', [ChallengeController::class, 'start'])->name('challenge.start')->middleware('auth');

// Trang tiến độ thử thách
Route::get('/challenge/{challenge}/progress', [ChallengeController::class, 'progress'])->name('challenge.progress')->middleware('auth');

// Hoàn thành task
Route::post('/challenge/{challenge}/task/{task}/complete', [ChallengeController::class, 'completeTask'])->name('task.complete')->middleware('auth');

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
 // Đảm bảo dòng này đúng địa chỉ file

// Nhóm này chỉ dùng middleware để bảo mật, không dùng prefix hay name chung nữa
Route::middleware(['auth', 'admin'])->group(function () {

    // Viết thẳng đường dẫn bạn muốn vào đây
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');

    // Đường dẫn cho nút Khóa/Mở
    Route::post('/admin/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

});
