<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller quản trị người dùng và hồ sơ cá nhân.
 *
 * Bao gồm:
 * - Quản lý danh sách user theo quyền
 * - Tạo/sửa tài khoản user
 * - Khóa/mở khóa tài khoản
 * - Hiển thị profile và thống kê tiến độ challenge
 */
class UserController extends Controller
{
    /**
     * Quản lý danh sách người dùng theo vai trò.
     *
     * Admin nhìn thấy tất cả người dùng và trưởng nhóm.
     * Useradmin chỉ thấy người dùng tham gia thử thách do họ tạo.
     */
    public function index()
    {
        $auth = Auth::user();

        if ($auth->role === 'admin') {
            // ADMIN: lấy tất cả UserAdmin và User, trừ chính mình
            $users = User::where('id', '!=', $auth->id)
                         ->whereIn('role', ['user', 'useradmin'])
                         ->get();
        }
        elseif ($auth->role === 'useradmin') {
            // USERADMIN: chỉ lấy những user tham gia thử thách do chính họ tạo
            $users = User::whereHas('userChallenges.challenge', function($query) use ($auth) {
                $query->where('created_by', $auth->id);
            })->where('role', 'user')->distinct()->get();
        } else {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }

        return view('admin.users', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user,useradmin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', '✅ Tạo tài khoản người dùng thành công!');
    }

    public function edit(User $user)
    {
        // Không cho phép chỉnh sửa tài khoản của chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Không thể chỉnh sửa tài khoản của chính mình!');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Không cho edit tài khoản admin của chính mình
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Không thể chỉnh sửa tài khoản của chính mình!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,useradmin',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Cập nhật password nếu có
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', '✅ Cập nhật thông tin người dùng thành công!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Không cho khóa tài khoản admin của chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể khóa tài khoản của chính mình!');
        }

        // Đảo ngược trạng thái
        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        $status = $user->is_active ? 'mở khóa' : 'khóa';
        return back()->with('success', "✅ Đã $status tài khoản {$user->name} thành công!");
    }

    /**
     * Hiển thị trang profile cho người dùng đã đăng nhập.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        $progressItems = $user->challengeProgress()
            ->with('challenge.category')
            ->orderByDesc('progress')
            ->get();

        $activeCount = $progressItems->where('progress', '<', 100)->count();
        $completedCount = $progressItems->where('progress', '>=', 100)->count();
        $totalCount = $progressItems->count();

        return view('shop.profile', compact('user', 'progressItems', 'activeCount', 'completedCount', 'totalCount'));
    }
}
