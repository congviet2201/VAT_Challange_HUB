<?php
/**
 * File purpose: app/Http/Middleware/IsAdmin.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware kiểm tra quyền truy cập admin.
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
 public function handle(Request $request, Closure $next)
{
    // Kiểm tra: Chỉ admin mới được truy cập
    if (Auth::check() && Auth::user()->role == 'admin') {
        return $next($request); // Cho phép đi tiếp
    }

    // Nếu không phải admin, đá về trang chủ với thông báo lỗi
    return redirect('/')->with('error', 'Bạn không có quyền truy cập vào khu vực này!');
}
}