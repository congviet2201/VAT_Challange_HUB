    <?php

/*
|--------------------------------------------------------------------------
| Web Routes - Định tuyến cho ứng dụng web
|--------------------------------------------------------------------------
|
| Đây là nơi định nghĩa tất cả các routes (đường dẫn) của ứng dụng.
| Routes sẽ kết nối URL với Controller để xử lý request.
|
*/

// THÊM DÒNG NÀY VÀO ĐẦU FILE WEB.PHP
use Illuminate\Support\Facades\Route;
// THÊM DÒNG NÀY VÀO ĐẦU FILE WEB.PHP
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Http\Controllers\User\GroupController as UserGroupController;
use App\Http\Controllers\UserAdmin\GroupController;
use App\Http\Controllers\UserAdmin\NotificationController;
use Illuminate\Support\Facades\Auth;
use App\Models\UserChallenge;

// ==========================================
// TRANG CHÍNH - Không cần đăng nhập
// ==========================================

// Trang chủ - Hiển thị danh sách danh mục thử thách
Route::get('/', [HomeController::class, 'index'])->name('home');

// Trang thử thách - Hiển thị tất cả thử thách hoặc thử thách theo danh mục
Route::get('/challenges', [HomeController::class, 'challenges'])->name('challenges');

// Trang danh mục - Hiển thị thử thách trong một danh mục cụ thể
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');

// Chi tiết thử thách - Xem thông tin chi tiết của một thử thách
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');

// Tìm kiếm thử thách - Tìm kiếm theo tên hoặc danh mục
Route::get('/search', [HomeController::class, 'search'])->name('search');

// ==========================================
// TRANG THÔNG TIN - Không cần đăng nhập
// ==========================================

// Trang giới thiệu
Route::get('/about', [PageController::class, 'about'])->name('about');

// Trang liên hệ
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

// ==========================================
// XÁC THỰC - Đăng nhập/Đăng ký
// ==========================================

// Hiển thị form đăng nhập (chỉ cho người chưa đăng nhập)
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');

// Xử lý đăng nhập (chỉ cho người chưa đăng nhập)
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');

// Hiển thị form đăng ký (chỉ cho người chưa đăng nhập)
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register')->middleware('guest');

// Xử lý đăng ký (chỉ cho người chưa đăng nhập)
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');

// Đăng xuất (chỉ cho người đã đăng nhập)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// CHỨC NĂNG THỬ THÁCH - Cần đăng nhập
// ==========================================

// Bắt đầu thử thách (tạo tiến độ và chuyển đến trang theo dõi)
Route::post('/challenge/{challenge}/start', [ChallengeController::class, 'start'])->name('challenge.start')->middleware('auth');
Route::post('/challenge/{challenge}/ai-roadmap', [ChallengeController::class, 'generateAiRoadmap'])->name('challenge.ai-roadmap')->middleware('auth');
Route::post('/challenge/{challenge}/ai-tasks/{task}/complete', [ChallengeController::class, 'completeAiTask'])->name('challenge.ai-task.complete')->middleware('auth');

// Trang theo dõi tiến độ thử thách
Route::get('/challenge/{challenge}/progress', [ChallengeController::class, 'progress'])->name('challenge.progress')->middleware('auth');

// Check-in hàng ngày cho thử thách
Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

// ==========================================
// NHÓM NGƯỜI DÙNG - Cần đăng nhập
// ==========================================

Route::middleware('auth')->group(function () {
    // Xem danh sách nhóm
    Route::get('/groups', [UserGroupController::class, 'index'])->name('user.groups.index');

    // Xem chi tiết nhóm
    Route::get('/groups/{group}', [UserGroupController::class, 'show'])->name('user.groups.show');

    // Tham gia nhóm
    Route::post('/groups/{group}/join', [UserGroupController::class, 'join'])->name('user.groups.join');

    // Rời khỏi nhóm
    Route::post('/groups/{group}/leave', [UserGroupController::class, 'leave'])->name('user.groups.leave');
});

// Trang dashboard của người dùng (hiển thị thử thách đang tham gia)
Route::get('/dashboard', function () {
    $userChallenges = UserChallenge::where('user_id', Auth::id())->get();
    return view('dashboard', compact('userChallenges'));
})->name('dashboard')->middleware('auth');

// Trang profile của người dùng (tất cả tài khoản đăng nhập đều có thể truy cập)
Route::get('/profile', [UserController::class, 'profile'])->name('profile')->middleware('auth');

// ==========================================
// QUẢN TRỊ VIÊN (ADMIN) - Cần quyền admin
// ==========================================

Route::middleware(['auth', 'admin'])->group(function () {
    // Quản lý người dùng
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

// ==========================================
// QUẢN TRỊ NHÓM (USERADMIN) - Cần quyền useradmin
// ==========================================

Route::middleware(['auth', 'useradmin'])->group(function () {
    // Quản lý nhóm
    Route::get('/useradmin/groups', [GroupController::class, 'index'])->name('useradmin.groups.index');
    Route::get('/useradmin/groups/create', [GroupController::class, 'create'])->name('useradmin.groups.create');
    Route::post('/useradmin/groups', [GroupController::class, 'store'])->name('useradmin.groups.store');
    Route::get('/useradmin/groups/{group}', [GroupController::class, 'show'])->name('useradmin.groups.show');
    Route::get('/useradmin/groups/{group}/edit', [GroupController::class, 'edit'])->name('useradmin.groups.edit');
    Route::put('/useradmin/groups/{group}', [GroupController::class, 'update'])->name('useradmin.groups.update');
    Route::post('/useradmin/groups/{group}/toggle', [GroupController::class, 'toggleStatus'])->name('useradmin.groups.toggle');

    // Thêm/xóa thành viên nhóm
    Route::get('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUserIndex'])->name('useradmin.groups.add-users');
    Route::post('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUser'])->name('useradmin.groups.add-users-store');
    Route::post('/useradmin/groups/{group}/remove-user/{user}', [GroupController::class, 'removeUser'])->name('useradmin.groups.remove-user');


    // Quản lý thông báo
    Route::get('/useradmin/notifications', [NotificationController::class, 'index'])->name('useradmin.notifications.index');
    Route::get('/useradmin/notifications/create', [NotificationController::class, 'create'])->name('useradmin.notifications.create');
    Route::post('/useradmin/notifications', [NotificationController::class, 'store'])->name('useradmin.notifications.store');
    Route::get('/useradmin/notifications/{notification}', [NotificationController::class, 'show'])->name('useradmin.notifications.show');
    Route::delete('/useradmin/notifications/{notification}', [NotificationController::class, 'destroy'])->name('useradmin.notifications.destroy');
});

// ==========================================
// MỤC TIÊU - Cần đăng nhập
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    Route::post('/goals/store', [GoalController::class, 'store'])->name('goals.store');
});
