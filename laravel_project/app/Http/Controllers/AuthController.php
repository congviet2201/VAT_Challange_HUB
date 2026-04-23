<?php

/**
 * File purpose: app/Http/Controllers/AuthController.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Controller xác thực người dùng:
 * đăng ký, đăng nhập và đăng xuất.
 *
 * Phụ thuộc chính:
 * - User model
 * - Laravel Auth/Hash
 * - WelcomeMail cho email chào mừng
 */
/**
 * Lớp AuthController: Xử lý các nghiệp vụ liên quan đến xác thực người dùng.
 * Cụ thể bao gồm: hiển thị giao diện, xử lý đăng ký tài khoản (kèm gửi email chào mừng),
 * xử lý đăng nhập, và đăng xuất khỏi hệ thống.
 */
class AuthController extends Controller
{
    /**
     * Show the registration page.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('shop.auth.register');
    }

    /**
     * Handle the registration form submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Hàm register(): Xử lý logic đăng ký tài khoản mới.
     * Validate dữ liệu form, tạo người dùng mới, tự động gửi email chào mừng (WelcomeMail),
     * và chuyển hướng người dùng về trang đăng nhập với thông báo thành công.
     */
    public function register(Request $request)
    {
        // Xác thực dữ liệu form
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:user,useradmin'
        ]);

        // Tạo user mới trong database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => null,
            'role' => $request->role,
            'is_active' => 1
        ]);

        // Gửi email chào mừng nếu có thể
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // Chuyển hướng về trang login với thông báo thành công
        return redirect()->route('auth.login')->with('success', 'Đăng ký thành công!');
    }

    /**
     * Show the login page.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('shop.auth.login');
    }

    /**
     * Process the login request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Hàm login(): Xử lý quá trình đăng nhập.
     * Kiểm tra thông tin email, mật khẩu. Nếu hợp lệ, hệ thống sẽ tái tạo lại session
     * để tránh lỗi bảo mật (session fixation), sau đó chuyển hướng người dùng về trang đích ban đầu.
     */
    public function login(Request $request)
    {
        // Xác thực dữ liệu người dùng
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember');

        // Thử đăng nhập bằng email và password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu đăng nhập thất bại, trả về lỗi và giữ lại email
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Hàm logout(): Xử lý việc đăng xuất người dùng khỏi hệ thống.
     * Xóa bỏ session hiện tại và tạo token CSRF mới để bảo vệ ứng dụng,
     * sau đó đưa người dùng về lại trang chủ.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }
}
