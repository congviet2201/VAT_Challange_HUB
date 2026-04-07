<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // 1. TRANG CHỦ: Lấy tất cả danh mục để hiển thị
    public function index()
    {
        // Lấy tất cả danh mục để hiển thị trên trang chủ
        $categories = Category::all();
        return view('shop.home', compact('categories'));
    }

    // 2. TRANG DANH MỤC: Lấy thử thách của một danh mục
    public function category($id)
    {
        $category = Category::findOrFail($id);
        $challenges = Challenge::where('category_id', $id)->get();
        return view('shop.category', compact('category', 'challenges'));
    }

    // 3. TRANG CHI TIẾT THỬ THÁCH: Lấy chi tiết thử thách và thử thách liên quan
    public function challengeDetail($id)
    {
        $challenge = Challenge::findOrFail($id);
        $category = $challenge->category;

        // Lấy các thử thách khác cùng danh mục để gợi ý
        $relatedChallenges = Challenge::where('category_id', $category->id)
            ->where('id', '!=', $id)
            ->limit(3)
            ->get();

        return view('shop.challenge-detail', compact('challenge', 'category', 'relatedChallenges'));
    }
}
