<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    // app/Http/Controllers/Admin/UserController.php

// app/Http/Controllers/Admin/UserController.php

public function index()
{
    $auth = auth::user();

    if ($auth->role === 'admin') {
        // ADMIN: Lấy tất cả (bao gồm cả UserAdmin và User thường)
        // Trừ chính bản thân mình ra để không tự khóa chính mình
        $users = User::where('id', '!=', $auth->id)->get();
    }
    else if ($auth->role === 'useradmin') {
        // USERADMIN: Chỉ lấy những User đang tham gia thử thách do UserAdmin này tạo
        // Logic: User -> qua bảng UserChallenge -> qua bảng Challenge (của UserAdmin này)
        $users = User::whereHas('userChallenges.challenge', function($query) use ($auth) {
            $query->where('created_by', $auth->id);
        })->where('role', 'user')->distinct()->get();
    }

    return view('admin.users', compact('users'));
}

    // Toggle Khóa/Mở khóa
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'mở khóa' : 'khóa';
        return back()->with('success', "Đã $status tài khoản của {$user->name} thành công!");
    }
}