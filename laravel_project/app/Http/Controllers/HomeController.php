<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Http\Request;

/**
 * HomeController - Controller xử lý các trang chính của website
 *
 * Controller này quản lý:
 * - Trang chủ (hiển thị danh mục)
 * - Trang danh mục (hiển thị thử thách trong danh mục)
 * - Trang chi tiết thử thách
 * - Tìm kiếm thử thách
 */
class HomeController extends Controller
{
    /**
     * Trang chủ - Hiển thị danh sách danh mục thử thách
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy tất cả danh mục và phân trang (6 danh mục/trang)
        $categories = Category::paginate(6);

        // Trả về view 'shop.home' với dữ liệu categories
        return view('shop.home', compact('categories'));
    }

    /**
     * Trang danh mục - Hiển thị thử thách trong một danh mục cụ thể
     *
     * @param int $id ID của danh mục
     * @return \Illuminate\View\View
     */
    public function category($id)
    {
        // Tìm danh mục theo ID, nếu không tìm thấy sẽ báo lỗi 404
        $category = Category::findOrFail($id);

        // Lấy tất cả thử thách thuộc danh mục này
        $challenges = Challenge::where('category_id', $id)->get();

        // Trả về view với dữ liệu category và challenges
        return view('shop.category', compact('category', 'challenges'));
    }

    /**
     * Trang tìm kiếm - Tìm thử thách theo tên hoặc danh mục
     *
     * @param Request $request Đối tượng request chứa dữ liệu từ form
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request, loại bỏ khoảng trắng thừa
        $query = trim($request->query('query', ''));

        // Tìm kiếm thử thách với quan hệ category
        $challenges = Challenge::with('category')
            ->when($query, function ($q, $query) {
                // Tạo chuỗi tìm kiếm với ký tự đại diện %
                $term = '%' . mb_strtolower($query, 'UTF-8') . '%';

                // Tìm trong title của challenge hoặc name của category
                $q->where(function ($q) use ($term) {
                    $q->whereRaw("LOWER(challenges.title) LIKE ? COLLATE utf8mb4_unicode_ci", [$term])
                        ->orWhereHas('category', function ($q) use ($term) {
                            $q->whereRaw("LOWER(categories.name) LIKE ? COLLATE utf8mb4_unicode_ci", [$term]);
                        });
                });
            })
            ->get();

        // Trả về view kết quả tìm kiếm
        return view('shop.search-results', compact('challenges', 'query'));
    }

    /**
     * Trang chi tiết thử thách - Hiển thị thông tin chi tiết của thử thách
     *
     * @param int $id ID của thử thách
     * @return \Illuminate\View\View
     */
    public function challengeDetail($id)
    {
        // Tìm thử thách theo ID, nếu không tìm thấy sẽ báo lỗi 404
        $challenge = Challenge::findOrFail($id);

        // Lấy danh mục của thử thách
        $category = $challenge->category;

        // Lấy các thử thách khác cùng danh mục để gợi ý (giới hạn 3)
        $relatedChallenges = Challenge::where('category_id', $category->id)
            ->where('id', '!=', $id) // Loại trừ thử thách hiện tại
            ->limit(3)
            ->get();

        // Trả về view với dữ liệu challenge, category và relatedChallenges
        return view('shop.challenge-detail', compact('challenge', 'category', 'relatedChallenges'));
    }

    public function challenges(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', $request->input('query', '')));
        $challenges = $this->buildChallengeSearchQuery($keyword)
            ->paginate(12)
            ->withQueryString();

        return view('shop.pages.challenges', compact('challenges', 'keyword'));
    }

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
