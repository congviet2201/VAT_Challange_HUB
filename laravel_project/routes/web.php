    <?php

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/challenges', [HomeController::class, 'challenges'])->name('challenges');
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');
Route::get('/challenge/{id}', [HomeController::class, 'challengeDetail'])->name('challenge.detail');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');

Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/challenge/{challenge}/start', [ChallengeController::class, 'start'])->name('challenge.start')->middleware('auth');
Route::post('/challenge/{challenge}/ai-roadmap', [ChallengeController::class, 'generateAiRoadmap'])->name('challenge.ai-roadmap')->middleware('auth');
Route::post('/challenge/{challenge}/ai-tasks/{task}/complete', [ChallengeController::class, 'completeAiTask'])->name('challenge.ai-task.complete')->middleware('auth');
Route::get('/challenge/{challenge}/progress', [ChallengeController::class, 'progress'])->name('challenge.progress')->middleware('auth');
Route::post('/checkin', [ChallengeController::class, 'checkin'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/groups', [UserGroupController::class, 'index'])->name('user.groups.index');
    Route::get('/groups/{group}', [UserGroupController::class, 'show'])->name('user.groups.show');
    Route::post('/groups/{group}/join', [UserGroupController::class, 'join'])->name('user.groups.join');
    Route::post('/groups/{group}/leave', [UserGroupController::class, 'leave'])->name('user.groups.leave');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

Route::get('/dashboard', function () {
    $userChallenges = UserChallenge::where('user_id', Auth::id())->get();
    return view('dashboard', compact('userChallenges'));
})->name('dashboard')->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

    Route::get('/admin/challenges', [AdminChallengeController::class, 'index'])->name('admin.challenges.index');
    Route::get('/admin/challenges/create', [AdminChallengeController::class, 'create'])->name('admin.challenges.create');
    Route::post('/admin/challenges', [AdminChallengeController::class, 'store'])->name('admin.challenges.store');
    Route::get('/admin/challenges/{challenge}', [AdminChallengeController::class, 'show'])->name('admin.challenges.show');
    Route::get('/admin/challenges/{challenge}/edit', [AdminChallengeController::class, 'edit'])->name('admin.challenges.edit');
    Route::put('/admin/challenges/{challenge}', [AdminChallengeController::class, 'update'])->name('admin.challenges.update');
    Route::delete('/admin/challenges/{challenge}', [AdminChallengeController::class, 'destroy'])->name('admin.challenges.destroy');
});

Route::middleware(['auth', 'useradmin'])->group(function () {
    Route::get('/useradmin/groups', [GroupController::class, 'index'])->name('useradmin.groups.index');
    Route::get('/useradmin/groups/create', [GroupController::class, 'create'])->name('useradmin.groups.create');
    Route::post('/useradmin/groups', [GroupController::class, 'store'])->name('useradmin.groups.store');
    Route::get('/useradmin/groups/{group}', [GroupController::class, 'show'])->name('useradmin.groups.show');
    Route::get('/useradmin/groups/{group}/edit', [GroupController::class, 'edit'])->name('useradmin.groups.edit');
    Route::put('/useradmin/groups/{group}', [GroupController::class, 'update'])->name('useradmin.groups.update');
    Route::post('/useradmin/groups/{group}/toggle', [GroupController::class, 'toggleStatus'])->name('useradmin.groups.toggle');
    Route::get('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUserIndex'])->name('useradmin.groups.add-users');
    Route::post('/useradmin/groups/{group}/add-users', [GroupController::class, 'addUser'])->name('useradmin.groups.add-users-store');
    Route::post('/useradmin/groups/{group}/remove-user/{user}', [GroupController::class, 'removeUser'])->name('useradmin.groups.remove-user');

    Route::get('/useradmin/notifications', [NotificationController::class, 'index'])->name('useradmin.notifications.index');
    Route::get('/useradmin/notifications/create', [NotificationController::class, 'create'])->name('useradmin.notifications.create');
    Route::post('/useradmin/notifications', [NotificationController::class, 'store'])->name('useradmin.notifications.store');
    Route::get('/useradmin/notifications/{notification}', [NotificationController::class, 'show'])->name('useradmin.notifications.show');
    Route::delete('/useradmin/notifications/{notification}', [NotificationController::class, 'destroy'])->name('useradmin.notifications.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/api/goals', [GoalController::class, 'store']);
    Route::post('/api/sub-goals/{id}/proof', [SubGoalController::class, 'submitProof']);
    Route::post('/api/sub-goals/{id}/complete', [SubGoalController::class, 'complete']);
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    Route::post('/goals/store', [GoalController::class, 'store'])->name('goals.store');
    Route::get('/goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
    Route::post('/api/goals/generate-subgoals', [GoalController::class, 'generateSubGoals']);
    Route::post('/api/goals/check-completion', [GoalController::class, 'checkGoalCompletion']);
});
