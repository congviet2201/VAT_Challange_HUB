<?php

namespace App\Http\Controllers\Admin;

use App\Models\Challenge;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChallengeController extends Controller
{
    /**
     * Hiển thị danh sách tất cả thử thách
     */
    public function index()
    {
        $challenges = Challenge::with('category', 'progress')->get();

        // Thêm thông tin số lượng người thực hiện
        $challenges->each(function ($challenge) {
            $challenge->total_users = $challenge->progress()->count();
            $challenge->completed_users = $challenge->progress()
                ->where('completed_at', '!=', null)
                ->count();
            $challenge->in_progress_users = $challenge->progress()
                ->where('completed_at', null)
                ->count();
        });

        return view('admin.challenges.index', compact('challenges'));
    }

    /**
     * Hiển thị form tạo thử thách mới
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.challenges.create', compact('categories'));
    }

    /**
     * Lưu thử thách mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'daily_time' => 'required|integer|min:1',
            'image' => 'nullable|string'
        ]);

        Challenge::create($request->all());

        return redirect()->route('admin.challenges.index')
            ->with('success', '✅ Thử thách mới đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết thử thách
     */
    public function show(Challenge $challenge)
    {
        $challenge->load('category', 'progress.user');

        $challenge->total_users = $challenge->progress()->count();
        $challenge->completed_users = $challenge->progress()
            ->where('completed_at', '!=', null)
            ->count();
        $challenge->in_progress_users = $challenge->progress()
            ->where('completed_at', null)
            ->count();

        $progressDetails = $challenge->progress()
            ->with('user')
            ->get();

        return view('admin.challenges.show', compact('challenge', 'progressDetails'));
    }

    /**
     * Hiển thị form chỉnh sửa thử thách
     */
    public function edit(Challenge $challenge)
    {
        $categories = Category::all();
        return view('admin.challenges.edit', compact('challenge', 'categories'));
    }

    /**
     * Cập nhật thử thách
     */
    public function update(Request $request, Challenge $challenge)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'daily_time' => 'required|integer|min:1',
            'image' => 'nullable|string'
        ]);

        $challenge->update($request->all());

        return redirect()->route('admin.challenges.index')
            ->with('success', '✅ Thử thách đã được cập nhật thành công!');
    }

    /**
     * Xóa thử thách
     */
    public function destroy(Challenge $challenge)
    {
        $title = $challenge->title;
        $challenge->delete();

        return redirect()->route('admin.challenges.index')
            ->with('success', "✅ Thử thách \"$title\" đã được xóa thành công!");
    }
}
