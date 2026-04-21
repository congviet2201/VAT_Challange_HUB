<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class GoalController extends Controller
{
    public function create()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $categories = Category::all();
        return view('goals.create', compact('categories'));
    }

    public function store(Request $request)
    {
        //  THÊM CHECK Ở ĐÂY
        if (!Auth::check()) {
            return redirect('/login');
        }

        $request->validate([
            'goals' => 'required|array|min:1',
            'goals.*.title' => 'required|string',
            'goals.*.category_id' => 'required|exists:categories,id',
        ]);

        foreach ($request->goals as $goal) {
            Goal::create([
                'user_id' => Auth::id(),
                'title' => $goal['title'],
                'category_id' => $goal['category_id'],
                'description' => $goal['description'] ?? null,
            ]);
        }

        return back()->with('success', 'Đã tạo mục tiêu!');
    }
}
