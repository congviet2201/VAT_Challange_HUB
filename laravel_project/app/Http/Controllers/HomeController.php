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
        // Lấy danh sách danh mục có phân trang, 6 danh mục mỗi trang.
        // Database → Category → Controller → View (shop.home)
        $categories = Category::paginate(6);

        return view('shop.home', compact('categories'));
    }
    /**
     * Trang danh sách challenge theo danh mục.
     */
    public function category($id)
    {
        $category = Category::findOrFail($id);
        $challenges = Challenge::where('category_id', $id)->get();

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
    // Hiển thị danh sách thử thách với phân trang và tìm kiếm
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
    // Logic tìm kiếm thử thách theo từ khóa, tìm trong title và description
    // định nghĩa trong một phương thức truy cập trong phạm vi lớp (class) 
    protected function buildChallengeSearchQuery(string $keyword)
    {
        // Em sử dụng Query Builder của Laravel.
        // Em dùng when() để kiểm tra nếu có keyword thì mới filter.
        //Sau đó dùng where và orWhere để tìm theo title hoặc description với toán tử LIKE.
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

// Trang chủ, shearch, danh sách challenge
// ->paginate(6), (9), (12)
// dùng paginate để phân trang“Để giữ lại các tham số tìm kiếm như keyword khi người dùng chuyển sang trang khác.

// 🔥 2. paginate() hoạt động như thế nào?

// 👉 Khi bạn dùng:

// Challenge::paginate(12);

// Laravel sẽ tự:

// ✔ Query SQL có LIMIT + OFFSET
// ✔ Chỉ lấy 12 bản ghi
// ✔ Tự tính tổng số trang
// ✔ Tạo link: ?page=1, ?page=2
// 👉 Ví dụ SQL phía sau:
// SELECT * FROM challenges LIMIT 12 OFFSET 0;