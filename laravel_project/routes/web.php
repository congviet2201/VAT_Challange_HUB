<?php
/**
 * File purpose: routes/web.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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

// Route: GET /
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route: GET /challenges
Route::get('/challenges', [HomeController::class, 'challenges'])->name('challenges');
// Route: GET /category/{id}
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');
// Route: GET /challenge/{id}
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');
// Route: GET /search
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Route: GET /about
Route::get('/about', [PageController::class, 'about'])->name('about');
// Route: GET /contact
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// Route: POST /contact
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

// Route: GET /login
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');
// Route: POST /login
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
// Route: GET /register
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register')->middleware('guest');
// Route: POST /register
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');
// Route: POST /logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route: POST /challenge/{challenge}/start
Route::post('/challenge/{challenge}/start', [ChallengeController::class, 'start'])->name('challenge.start')->middleware('auth');
// Route: POST /challenge/{challenge}/ai-roadmap
Route::post('/challenge/{challenge}/ai-roadmap', [ChallengeController::class, 'generateAiRoadmap'])->name('challenge.ai-roadmap')->middleware('auth');
// Route: POST /challenge/{challenge}/ai-tasks/{task}/complete
Route::post('/challenge/{challenge}/ai-tasks/{task}/complete', [ChallengeController::class, 'completeAiTask'])->name('challenge.ai-task.complete')->middleware('auth');
// Route: GET /challenge/{challenge}/progress
Route::get('/challenge/{challenge}/progress', [ChallengeController::class, 'progress'])->name('challenge.progress')->middleware('auth');
// Route: POST /checkin
Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

Route::middleware('auth')->group(function () {
    // Route: GET /groups
    Route::get('/groups', [UserGroupController::class, 'index'])->name('user.groups.index');
    // Route: GET /groups/{group}
    Route::get('/groups/{group}', [UserGroupController::class, 'show'])->name('user.groups.show');
    // Route: POST /groups/{group}/join
    Route::post('/groups/{group}/join', [UserGroupController::class, 'join'])->name('user.groups.join');
    // Route: POST /groups/{group}/leave
    Route::post('/groups/{group}/leave', [UserGroupController::class, 'leave'])->name('user.groups.leave');
    // Route: GET /profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

Route::get('/dashboard', function () {
    $userChallenges = UserChallenge::where('user_id', Auth::id())->get();
    return view('dashboard', compact('userChallenges'));
})->name('dashboard')->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    // Route: GET /admin/users
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    // Route: GET /admin/users/create
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    // Route: POST /admin/users
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    // Route: GET /admin/users/{user}/edit
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    // Route: PUT /admin/users/{user}
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    // Route: POST /admin/users/{id}/toggle
    Route::post('/admin/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

    // Route: GET /admin/challenges
    Route::get('/admin/challenges', [AdminChallengeController::class, 'index'])->name('admin.challenges.index');
    // Route: GET /admin/challenges/create
    Route::get('/admin/challenges/create', [AdminChallengeController::class, 'create'])->name('admin.challenges.create');
    // Route: POST /admin/challenges
    Route::post('/admin/challenges', [AdminChallengeController::class, 'store'])->name('admin.challenges.store');
    // Route: GET /admin/challenges/{challenge}
    Route::get('/admin/challenges/{challenge}', [AdminChallengeController::class, 'show'])->name('admin.challenges.show');
    // Route: GET /admin/challenges/{challenge}/edit
    Route::get('/admin/challenges/{challenge}/edit', [AdminChallengeController::class, 'edit'])->name('admin.challenges.edit');
    // Route: PUT /admin/challenges/{challenge}
    Route::put('/admin/challenges/{challenge}', [AdminChallengeController::class, 'update'])->name('admin.challenges.update');
    // Route: DELETE /admin/challenges/{challenge}
    Route::delete('/admin/challenges/{challenge}', [AdminChallengeController::class, 'destroy'])->name('admin.challenges.destroy');
});

Route::middleware(['auth', 'useradmin'])->group(function () {
    // Route: GET /useradmin/groups
    Route::get('/useradmin/groups', [GroupController::class, 'index'])->name('useradmin.groups.index');
    // Route: GET /useradmin/groups/create
    Route::get('/useradmin/groups/create', [GroupController::class, 'create'])->name('useradmin.groups.create');
    // Route: POST /useradmin/groups
    Route::post('/useradmin/groups', [GroupController::class, 'store'])->name('useradmin.groups.store');
    // Route: GET /useradmin/groups/{group}
    Route::get('/useradmin/groups/{group}', [GroupController::class, 'show'])->name('useradmin.groups.show');
    // Route: GET /useradmin/groups/{group}/edit
    Route::get('/useradmin/groups/{group}/edit', [GroupController::class, 'edit'])->name('useradmin.groups.edit');
    // Route: PUT /useradmin/groups/{group}
    Route::put('/useradmin/groups/{group}', [GroupController::class, 'update'])->name('useradmin.groups.update');
    // Route: POST /useradmin/groups/{group}/toggle
    Route::post('/useradmin/groups/{group}/toggle', [GroupController::class, 'toggleStatus'])->name('useradmin.groups.toggle');
    // Route: GET /useradmin/groups/{group}/add-users
    Route::get('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUserIndex'])->name('useradmin.groups.add-users');
    // Route: POST /useradmin/groups/{group}/add-users
    Route::post('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUser'])->name('useradmin.groups.add-users-store');
    // Route: POST /useradmin/groups/{group}/remove-user/{user}
    Route::post('/useradmin/groups/{group}/remove-user/{user}', [GroupController::class, 'removeUser'])->name('useradmin.groups.remove-user');

    // Route: GET /useradmin/notifications
    Route::get('/useradmin/notifications', [NotificationController::class, 'index'])->name('useradmin.notifications.index');
    // Route: GET /useradmin/notifications/create
    Route::get('/useradmin/notifications/create', [NotificationController::class, 'create'])->name('useradmin.notifications.create');
    // Route: POST /useradmin/notifications
    Route::post('/useradmin/notifications', [NotificationController::class, 'store'])->name('useradmin.notifications.store');
    // Route: GET /useradmin/notifications/{notification}
    Route::get('/useradmin/notifications/{notification}', [NotificationController::class, 'show'])->name('useradmin.notifications.show');
    // Route: DELETE /useradmin/notifications/{notification}
    Route::delete('/useradmin/notifications/{notification}', [NotificationController::class, 'destroy'])->name('useradmin.notifications.destroy');
});

Route::middleware('auth')->group(function () {
    // Route: POST /api/goals
    Route::post('/api/goals', [GoalController::class, 'store']);
    // Route: POST /api/sub-goals/{id}/proof
    Route::post('/api/sub-goals/{id}/proof', [SubGoalController::class, 'submitProof']);
    // Route: POST /api/sub-goals/{id}/complete
    Route::post('/api/sub-goals/{id}/complete', [SubGoalController::class, 'complete']);
    // Route: GET /goals
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    // Route: GET /goals/create
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    // Route: POST /goals/store
    Route::post('/goals/store', [GoalController::class, 'store'])->name('goals.store');
    // Route: GET /goals/{goal}
    Route::get('/goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
    // Route: POST /api/goals/generate-subgoals
    Route::post('/api/goals/generate-subgoals', [GoalController::class, 'generateSubGoals']);
    // Route: POST /api/goals/check-completion
    Route::post('/api/goals/check-completion', [GoalController::class, 'checkGoalCompletion']);
});
