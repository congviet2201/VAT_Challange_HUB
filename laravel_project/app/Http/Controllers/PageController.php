<?php
/**
 * File purpose: app/Http/Controllers/PageController.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
     * HĂ m sendContact(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
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
