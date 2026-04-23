<?php

namespace App\Http\Controllers\UserAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller quản lý nhóm dành cho useradmin.
 *
 * Phạm vi:
 * - CRUD nhóm do useradmin tạo
 * - Quản lý thành viên trong nhóm
 * - Quản lý danh sách challenge gán vào nhóm
 */
class GroupController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        // Chỉ xem groups do chính mình tạo
        $groups = Group::where('created_by', $auth->id)->get();
        return view('useradmin.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('useradmin.groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'is_active' => 1,
        ]);

        return redirect()->route('useradmin.groups.index')
            ->with('success', '✅ Tạo nhóm thành công!');
    }

    public function edit(Group $group)
    {
        // Kiểm tra chủ sở hữu nhóm
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa nhóm này!');
        }

        return view('useradmin.groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        // Kiểm tra chủ sở hữu nhóm
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa nhóm này!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('useradmin.groups.index')
            ->with('success', '✅ Cập nhật nhóm thành công!');
    }

    public function toggleStatus(Group $group)
    {
        // Kiểm tra chủ sở hữu nhóm
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền thay đổi nhóm này!');
        }

        $group->is_active = $group->is_active ? 0 : 1;
        $group->save();

        $status = $group->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        return back()->with('success', "✅ Đã $status nhóm {$group->name} thành công!");
    }

    // Hiển thị danh sách users để thêm vào nhóm
    public function addUserIndex(Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        // Lấy danh sách users không thuộc nhóm này
        $existingUserIds = $group->users()->pluck('users.id')->toArray();
        $availableUsers = User::where('role', 'user')
            ->where('is_active', 1)
            ->whereNotIn('id', $existingUserIds)
            ->get();

        return view('useradmin.groups.add-users', compact('group', 'availableUsers'));
    }

    // Thêm user vào nhóm
    public function addUser(Request $request, Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        foreach ($request->user_ids as $userId) {
            $group->users()->attach($userId);
        }

        return redirect()->route('useradmin.groups.show', $group->id)
            ->with('success', '✅ Thêm thành viên vào nhóm thành công!');
    }

    // Xem chi tiết nhóm và danh sách thành viên
    public function show(Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền xem nhóm này!');
        }

        $members = $group->users()->get();
        return view('useradmin.groups.show', compact('group', 'members'));
    }

    // Xóa user khỏi nhóm
    public function removeUser(Group $group, User $user)
    {
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        $group->users()->detach($user->id);

        return back()->with('success', "✅ Đã xóa {$user->name} khỏi nhóm!");
    }

    // Hiển thị danh sách thử thách trong nhóm
    public function challengeIndex(Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền xem nhóm này!');
        }

        $challenges = $group->challenges()->with('category')->get();
        return view('useradmin.groups.challenges', compact('group', 'challenges'));
    }

    // Hiển thị form thêm thử thách vào nhóm
    public function addChallengeIndex(Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        // Lấy danh sách thử thách không thuộc nhóm này
        $existingChallengeIds = $group->challenges()->pluck('challenges.id')->toArray();
        $availableChallenges = Challenge::with('category')
            ->whereNotIn('id', $existingChallengeIds)
            ->get()
            ->groupBy('category.name');

        return view('useradmin.groups.add-challenges', compact('group', 'availableChallenges'));
    }

    // Thêm thử thách vào nhóm
    public function addChallenge(Request $request, Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        $request->validate([
            'challenge_ids' => 'required|array|min:1',
            'challenge_ids.*' => 'integer|exists:challenges,id',
        ]);

        foreach ($request->challenge_ids as $challengeId) {
            $group->challenges()->attach($challengeId);
        }

        return redirect()->route('useradmin.groups.challenges', $group->id)
            ->with('success', '✅ Thêm thử thách vào nhóm thành công!');
    }

    // Xóa thử thách khỏi nhóm
    public function removeChallenge(Group $group, $challengeId)
    {
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        $group->challenges()->detach($challengeId);

        return back()->with('success', '✅ Đã xóa thử thách khỏi nhóm!');
    }
}
