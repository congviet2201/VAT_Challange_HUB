<?php
/**
 * File purpose: app/Http/Middleware/IsUserAdmin.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware kiểm tra quyền truy cập useradmin.
 */
class IsUserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'useradmin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
    }
}
