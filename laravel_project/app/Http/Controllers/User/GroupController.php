<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $groups = Group::with('creator')
            ->withCount(['users', 'challenges'])
            ->where('is_active', true)
            ->latest()
            ->get();

        $joinedGroupIds = $user->groups()->pluck('groups.id')->toArray();

        return view('shop.groups.index', compact('groups', 'joinedGroupIds'));
    }

    public function show(Group $group)
    {
        $user = Auth::user();
        $isMember = $group->users()->where('users.id', $user->id)->exists();

        if (!$group->is_active && !$isMember) {
            return redirect()->route('user.groups.index')
                ->with('error', 'Nhóm này hiện không hoạt động.');
        }

        $group->load(['creator', 'challenges.category', 'users']);

        return view('shop.groups.show', compact('group', 'isMember'));
    }

    public function join(Group $group)
    {
        if (!$group->is_active) {
            return back()->with('error', 'Bạn không thể tham gia nhóm đã bị vô hiệu hóa.');
        }

        $user = Auth::user();

        if ($group->users()->where('users.id', $user->id)->exists()) {
            return back()->with('info', 'Bạn đã tham gia nhóm này rồi.');
        }

        $group->users()->syncWithoutDetaching([$user->id]);

        return back()->with('success', "Tham gia nhóm {$group->name} thành công!");
    }

    public function leave(Group $group)
    {
        $user = Auth::user();

        if (!$group->users()->where('users.id', $user->id)->exists()) {
            return back()->with('info', 'Bạn chưa tham gia nhóm này.');
        }

        $group->users()->detach($user->id);

        return back()->with('success', "Bạn đã rời nhóm {$group->name}.");
    }
}
