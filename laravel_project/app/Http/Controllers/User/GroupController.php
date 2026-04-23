<?php
/**
 * File purpose: app/Http/Controllers/User/GroupController.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

/**
 * Controller cho user thường tương tác với nhóm.
 *
 * Bao gồm: xem danh sách nhóm, xem chi tiết, tham gia và rời nhóm.
 */
class GroupController extends Controller
{
    /**
     * HĂ m index(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
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

    /**
     * HĂ m show(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
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

    /**
     * HĂ m join(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
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

    /**
     * HĂ m leave(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
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
