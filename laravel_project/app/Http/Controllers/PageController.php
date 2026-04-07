<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Trang Giới thiệu
    public function about()
    {
        return view('shop.pages.about');
    }

    // Trang Liên hệ
    public function contact()
    {
        return view('shop.pages.contact');
    }

    // Gửi form liên hệ
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        // Có thể gửi email hoặc lưu vào database tùy theo yêu cầu
        // Tạm để giữ đơn giản, chỉ trả về toast success

        return back()->with('success', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ sớm.');
    }
}
