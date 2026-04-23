<?php
/**
 * File purpose: app/Http/Controllers/PageController.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller cho các trang nội dung tĩnh và form liên hệ.
 */
class PageController extends Controller
{
    /**
     * Show the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('shop.pages.about');
    }

    /**
     * Show the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('shop.pages.contact');
    }

    /**
     * Handle the contact form submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Hàm sendContact(): Xử lý form liên hệ được gửi từ người dùng.
     * Xác thực các trường dữ liệu bắt buộc (tên, email, tiêu đề, nội dung).
     * Trả về thông báo thành công sau khi xác thực hợp lệ (chưa tích hợp gửi mail thực tế).
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        // Dữ liệu hợp lệ, có thể gửi email hoặc lưu dữ liệu nếu muốn.
        // Ở đây giữ đơn giản và trả về thông báo thành công.

        return back()->with('success', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ sớm.');
    }
}
