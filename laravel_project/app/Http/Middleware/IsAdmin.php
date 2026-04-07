<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
 public function handle(Request $request, Closure $next)
{
    // Kiểm tra: Nếu đã đăng nhập VÀ (role là admin HOẶC useradmin)
    if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'useradmin')) {
        return $next($request); // Cho phép đi tiếp
    }

    // Nếu không phải admin, đá về trang chủ với thông báo lỗi
    return redirect('/')->with('error', 'Bạn không có quyền truy cập vào khu vực này!');
}
}
// app/Http/Middleware/IsAdmin.php