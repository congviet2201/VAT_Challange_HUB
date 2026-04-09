<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $auth = Auth::user();

        if ($auth->role === 'admin') {
            // ADMIN: Lấy tất cả UserAdmin và User (loại trừ chính mình)
            $users = User::where('id', '!=', $auth->id)
                         ->whereIn('role', ['user', 'useradmin'])
                         ->get();
        }
        else if ($auth->role === 'useradmin') {
            // USERADMIN: Chỉ lấy User đang tham gia thử thách do mình tạo
            $users = User::whereHas('userChallenges.challenge', function($query) use ($auth) {
                $query->where('created_by', $auth->id);
            })->where('role', 'user')->distinct()->get();
        } else {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }

        return view('admin.users', compact('users'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Đảo ngược trạng thái
        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        $status = $user->is_active ? 'mở khóa' : 'khóa';
        return back()->with('success', "Đã $status tài khoản {$user->name} thành công!");
    }
}
