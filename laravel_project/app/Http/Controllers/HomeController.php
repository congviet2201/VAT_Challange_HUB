<?php

/**
 * File purpose: app/Http/Controllers/HomeController.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Http\Request;

/**
 * Controller phục vụ các trang public:
 * trang chủ, danh mục, chi tiết challenge và tìm kiếm.
 */
class HomeController extends Controller
{
    /**
     * Trang chủ hiển thị danh sách danh mục.
     */
    public function index()
    {
        // Lấy tất cả danh mục và phân trang (6 danh mục/trang)
        $categories = Category::paginate(6);

        // Trả về view 'shop.home' với dữ liệu categories
        return view('shop.home', compact('categories'));
    }
    /**
     * Trang danh sách challenge theo danh mục.
     */
    public function category($id)
    {
        $category = Category::findOrFail($id);
        $challenges = Challenge::where('category_id', $id)->get();

        // Trả về view với dữ liệu category và challenges
        return view('shop.category', compact('category', 'challenges'));
    }

    /**
     * Tìm kiếm challenge theo từ khóa.
     */
    public function search(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', $request->input('query', '')));
        $challenges = $this->buildChallengeSearchQuery($keyword)
            ->paginate(9)
            ->withQueryString();

        return view('shop.pages.challenges', compact('challenges', 'keyword'));
    }
    /**
     * Trang chi tiết challenge.
>>>>>>> origin/main
     */
    public function challengeDetail($id)
    {
        $challenge = Challenge::findOrFail($id);
        $category = $challenge->category;
        $relatedChallenges = Challenge::where('category_id', $category->id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        return view('shop.challenge-detail', compact('challenge', 'category', 'relatedChallenges'));
    }

    /**
     * Trang tổng hợp tất cả challenge (có phân trang).
     */
    public function challenges(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', $request->input('query', '')));
        $challenges = $this->buildChallengeSearchQuery($keyword)
            ->paginate(12)
            ->withQueryString();

        return view('shop.pages.challenges', compact('challenges', 'keyword'));
    }

    /**
     * Dựng query tìm kiếm dùng chung cho search/challenges.
     */
    protected function buildChallengeSearchQuery(string $keyword)
    {
        return Challenge::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                });
            })
            ->latest();
    }
}
