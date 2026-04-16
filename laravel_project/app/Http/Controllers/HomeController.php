<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('shop.home', compact('categories'));
    }

    public function category($id)
    {
        $category = Category::findOrFail($id);
        $challenges = Challenge::where('category_id', $id)->get();

        return view('shop.category', compact('category', 'challenges'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', $request->input('query', '')));
        $challenges = $this->buildChallengeSearchQuery($keyword)
            ->paginate(9)
            ->withQueryString();

        return view('shop.pages.challenges', compact('challenges', 'keyword'));
    }

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
