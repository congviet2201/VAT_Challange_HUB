<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Trang chủ
    public function index()
    {
        // Lấy tất cả danh mục để hiển thị trên trang chủ
        $categories = Category::all();

        return view('shop.home', compact('categories'));
    }

    // 👉 ĐẶT Ở ĐÂY (bên dưới index)
    public function category($id)
    {
        $category = Category::findOrFail($id);

        $challenges = Challenge::where('category_id', $id)->get();

        return view('shop.category', compact('category', 'challenges'));
    }
}
