<?php
/**
 * Mục đích file: routes/web.php
 * Khai báo tất cả các định tuyến (routes) dành cho giao diện Web và một số API nội bộ.
 */

/**
 * Khai báo toàn bộ HTTP routes của ứng dụng:
 * - Public pages (home/search/challenge detail)
 * - Auth routes
 * - Challenge + AI roadmap routes
 * - Admin/UserAdmin panels
 * - Goal/Sub-goal APIs
 */

use App\Http\Controllers\Admin\ChallengeController as AdminChallengeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubGoalController;
use App\Http\Controllers\User\GroupController as UserGroupController;
use App\Http\Controllers\UserAdmin\GroupController;
use App\Http\Controllers\UserAdmin\NotificationController;
use App\Http\Controllers\UserController;
use App\Models\UserChallenge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route: Trang chủ (Hiển thị giao diện chính của ứng dụng)
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route: Trang danh sách tất cả các thử thách
Route::get('/challenges', [HomeController::class, 'challenges'])->name('challenges');
// Route: Trang hiển thị thử thách theo danh mục cụ thể
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');
// Route: Trang chi tiết của một thử thách
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');
// Route: Chức năng tìm kiếm thử thách/bài viết
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Route: Trang giới thiệu về nền tảng
Route::get('/about', [PageController::class, 'about'])->name('about');
// Route: Trang liên hệ hỗ trợ
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// Route: Xử lý gửi thông tin liên hệ từ người dùng
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

// Route: Trang hiển thị form đăng nhập
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');
// Route: Xử lý logic đăng nhập
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
// Route: Trang hiển thị form đăng ký tài khoản
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register')->middleware('guest');
// Route: Xử lý logic đăng ký tài khoản mới
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');
// Route: Xử lý đăng xuất người dùng (yêu cầu đã đăng nhập)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route: Xử lý bắt đầu tham gia một thử thách (yêu cầu đã đăng nhập)
Route::post('/challenge/{challenge}/start', [ChallengeController::class, 'start'])->name('challenge.start')->middleware('auth');
// Route: Sinh lộ trình (roadmap) học tập cho thử thách bằng AI (yêu cầu đã đăng nhập)
Route::post('/challenge/{challenge}/ai-roadmap', [ChallengeController::class, 'generateAiRoadmap'])->name('challenge.ai-roadmap')->middleware('auth');
// Route: Nộp minh chứng hoàn thành một nhiệm vụ AI (yêu cầu đã đăng nhập)
Route::post('/challenge/{challenge}/ai-tasks/{task}/complete', [ChallengeController::class, 'completeAiTask'])->name('challenge.ai-task.complete')->middleware('auth');
// Route: Xem tiến độ của thử thách đang tham gia (yêu cầu đã đăng nhập)
Route::get('/challenge/{challenge}/progress', [ChallengeController::class, 'progress'])->name('challenge.progress')->middleware('auth');
// Route: Thực hiện điểm danh (check-in) hàng ngày cho thử thách (yêu cầu đã đăng nhập)
Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

Route::middleware('auth')->group(function () {
    // Route: Xem danh sách các nhóm mà người dùng có thể tham gia
    Route::get('/groups', [UserGroupController::class, 'index'])->name('user.groups.index');
    // Route: Xem chi tiết thông tin của một nhóm cụ thể
    Route::get('/groups/{group}', [UserGroupController::class, 'show'])->name('user.groups.show');
    // Route: Xử lý xin gia nhập vào một nhóm
    Route::post('/groups/{group}/join', [UserGroupController::class, 'join'])->name('user.groups.join');
    // Route: Xử lý rời khỏi nhóm đang tham gia
    Route::post('/groups/{group}/leave', [UserGroupController::class, 'leave'])->name('user.groups.leave');
    // Route: Xem trang thông tin cá nhân (Profile) của người dùng
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

// Route: Trang Dashboard (Bảng điều khiển cá nhân) hiển thị tổng quan thông tin, tiến trình
Route::get('/dashboard', function () {
    $userChallenges = UserChallenge::where('user_id', Auth::id())->get();
    return view('dashboard', compact('userChallenges'));
})->name('dashboard')->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    // Route: (Admin) Xem danh sách toàn bộ người dùng trong hệ thống
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    // Route: (Admin) Hiển thị form thêm mới một người dùng
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    // Route: (Admin) Xử lý lưu thông tin người dùng mới
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    // Route: (Admin) Hiển thị form chỉnh sửa thông tin người dùng
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    // Route: (Admin) Xử lý cập nhật thông tin người dùng đã chỉnh sửa
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    // Route: (Admin) Bật/tắt trạng thái hoạt động (khóa/mở khóa) của người dùng
    Route::post('/admin/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

    // Route: (Admin) Xem danh sách tất cả thử thách trong hệ thống
    Route::get('/admin/challenges', [AdminChallengeController::class, 'index'])->name('admin.challenges.index');
    // Route: (Admin) Hiển thị form tạo mới một thử thách
    Route::get('/admin/challenges/create', [AdminChallengeController::class, 'create'])->name('admin.challenges.create');
    // Route: (Admin) Xử lý lưu thông tin thử thách mới
    Route::post('/admin/challenges', [AdminChallengeController::class, 'store'])->name('admin.challenges.store');
    // Route: (Admin) Xem chi tiết của một thử thách cụ thể
    Route::get('/admin/challenges/{challenge}', [AdminChallengeController::class, 'show'])->name('admin.challenges.show');
    // Route: (Admin) Hiển thị form chỉnh sửa nội dung thử thách
    Route::get('/admin/challenges/{challenge}/edit', [AdminChallengeController::class, 'edit'])->name('admin.challenges.edit');
    // Route: (Admin) Xử lý cập nhật thông tin thử thách
    Route::put('/admin/challenges/{challenge}', [AdminChallengeController::class, 'update'])->name('admin.challenges.update');
    // Route: (Admin) Xóa một thử thách khỏi hệ thống
    Route::delete('/admin/challenges/{challenge}', [AdminChallengeController::class, 'destroy'])->name('admin.challenges.destroy');
});

Route::middleware(['auth', 'useradmin'])->group(function () {
    // Route: (UserAdmin) Xem danh sách các nhóm được quản lý
    Route::get('/useradmin/groups', [GroupController::class, 'index'])->name('useradmin.groups.index');
    // Route: (UserAdmin) Hiển thị form tạo một nhóm mới
    Route::get('/useradmin/groups/create', [GroupController::class, 'create'])->name('useradmin.groups.create');
    // Route: (UserAdmin) Xử lý lưu nhóm mới vào cơ sở dữ liệu
    Route::post('/useradmin/groups', [GroupController::class, 'store'])->name('useradmin.groups.store');
    // Route: (UserAdmin) Xem chi tiết của một nhóm cụ thể
    Route::get('/useradmin/groups/{group}', [GroupController::class, 'show'])->name('useradmin.groups.show');
    // Route: (UserAdmin) Hiển thị form chỉnh sửa thông tin nhóm
    Route::get('/useradmin/groups/{group}/edit', [GroupController::class, 'edit'])->name('useradmin.groups.edit');
    // Route: (UserAdmin) Xử lý cập nhật thông tin nhóm sau khi sửa
    Route::put('/useradmin/groups/{group}', [GroupController::class, 'update'])->name('useradmin.groups.update');
    // Route: (UserAdmin) Bật/tắt trạng thái (đóng/mở) của một nhóm
    Route::post('/useradmin/groups/{group}/toggle', [GroupController::class, 'toggleStatus'])->name('useradmin.groups.toggle');
    // Route: (UserAdmin) Hiển thị giao diện thêm người dùng vào nhóm
    Route::get('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUserIndex'])->name('useradmin.groups.add-users');
    // Route: (UserAdmin) Xử lý thêm người dùng vào nhóm
    Route::post('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUser'])->name('useradmin.groups.add-users-store');
    // Route: (UserAdmin) Xử lý loại bỏ người dùng khỏi nhóm
    Route::post('/useradmin/groups/{group}/remove-user/{user}', [GroupController::class, 'removeUser'])->name('useradmin.groups.remove-user');

    // Route: (UserAdmin) Xem danh sách thử thách của nhóm
    Route::get('/useradmin/groups/{group}/challenges', [GroupController::class, 'challengeIndex'])->name('useradmin.groups.challenges');
    // Route: (UserAdmin) Hiển thị giao diện thêm thử thách vào nhóm
    Route::get('/useradmin/groups/{group}/add-challenges', [GroupController::class, 'addChallengeIndex'])->name('useradmin.groups.add-challenges');
    // Route: (UserAdmin) Xử lý thêm thử thách vào nhóm
    Route::post('/useradmin/groups/{group}/add-challenges', [GroupController::class, 'addChallenge'])->name('useradmin.groups.add-challenges-store');
    // Route: (UserAdmin) Xử lý xóa thử thách khỏi nhóm
    Route::post('/useradmin/groups/{group}/remove-challenge/{challengeId}', [GroupController::class, 'removeChallenge'])->name('useradmin.groups.remove-challenge');

    // Route: (UserAdmin) Xem danh sách các thông báo đã được tạo gửi tới nhóm
    Route::get('/useradmin/notifications', [NotificationController::class, 'index'])->name('useradmin.notifications.index');
    // Route: (UserAdmin) Hiển thị form tạo mới thông báo
    Route::get('/useradmin/notifications/create', [NotificationController::class, 'create'])->name('useradmin.notifications.create');
    // Route: (UserAdmin) Xử lý lưu và phát thông báo đến nhóm
    Route::post('/useradmin/notifications', [NotificationController::class, 'store'])->name('useradmin.notifications.store');
    // Route: (UserAdmin) Xem chi tiết của một thông báo
    Route::get('/useradmin/notifications/{notification}', [NotificationController::class, 'show'])->name('useradmin.notifications.show');
    // Route: (UserAdmin) Xóa thông báo đã tạo
    Route::delete('/useradmin/notifications/{notification}', [NotificationController::class, 'destroy'])->name('useradmin.notifications.destroy');
});

Route::middleware('auth')->group(function () {
    // Route: [API] Tạo một mục tiêu (goal) mới (Thường dùng cho xử lý ngầm qua AJAX)
    Route::post('/api/goals', [GoalController::class, 'store']);
    // Route: [API] Gửi lên minh chứng hoàn thành cho nhiệm vụ phụ (sub-goal)
    Route::post('/api/sub-goals/{id}/proof', [SubGoalController::class, 'submitProof']);
    // Route: [API] Xác nhận hoàn tất nhiệm vụ phụ (sub-goal)
    Route::post('/api/sub-goals/{id}/complete', [SubGoalController::class, 'complete']);
    
    // Route: Xem trang danh sách các mục tiêu lớn (Goals) của người dùng
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    // Route: Hiển thị giao diện tạo mới mục tiêu lớn
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    // Route: Xử lý lưu dữ liệu tạo mới mục tiêu lớn từ form
    Route::post('/goals/store', [GoalController::class, 'store'])->name('goals.store');
    // Route: Xem trang chi tiết của một mục tiêu lớn cụ thể, bao gồm các nhiệm vụ phụ
    Route::get('/goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
    
    // Route: [API] Gọi AI sinh ra danh sách nhiệm vụ phụ cho một mục tiêu lớn
    Route::post('/api/goals/generate-subgoals', [GoalController::class, 'generateSubGoals']);
    // Route: [API] Kiểm tra trạng thái hoàn thành tổng thể của mục tiêu lớn
    Route::post('/api/goals/check-completion', [GoalController::class, 'checkGoalCompletion']);
});
