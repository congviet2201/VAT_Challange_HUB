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
        $categories = Category::paginate(6);
        return view('shop.home', compact('categories'));
    }

    // 2. TRANG DANH MỤC: Lấy thử thách của một danh mục
    public function category($id)
    {
        $category = Category::findOrFail($id);
        $challenges = Challenge::where('category_id', $id)->get();
        return view('shop.category', compact('category', 'challenges'));
    }

    // 2.5 TRANG TÌM KIẾM: Tìm thử thách theo tên và thể loại
    public function search(Request $request)
    {
        $query = trim($request->query('query', ''));

        $challenges = Challenge::with('category')
            ->when($query, function ($q, $query) {
                $term = '%' . mb_strtolower($query, 'UTF-8') . '%';

                $q->where(function ($q) use ($term) {
                    $q->whereRaw("LOWER(challenges.title) LIKE ? COLLATE utf8mb4_unicode_ci", [$term])
                        ->orWhereHas('category', function ($q) use ($term) {
                            $q->whereRaw("LOWER(categories.name) LIKE ? COLLATE utf8mb4_unicode_ci", [$term]);
                        });
                });
            })
            ->get();

        return view('shop.search-results', compact('challenges', 'query'));
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
