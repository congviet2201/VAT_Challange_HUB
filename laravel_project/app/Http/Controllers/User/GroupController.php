<?php
/**
 * File purpose: app/Http/Controllers/User/GroupController.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
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
     * Hàm index(): Hiển thị danh sách tất cả các nhóm đang hoạt động (is_active = true) trên hệ thống.
     * Lấy thêm thông tin người tạo nhóm, số lượng thành viên, số lượng thử thách.
     * Đồng thời, lấy danh sách ID các nhóm mà User hiện tại đã tham gia để kiểm tra hiển thị nút "Tham gia".
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
     * Hàm show(): Hiển thị thông tin chi tiết của một nhóm, kèm theo danh sách thử thách và thành viên.
     * Nếu nhóm không hoạt động, chỉ các thành viên cũ trong nhóm mới được phép xem thông tin.
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
     * Hàm join(): Xử lý logic cho người dùng tham gia vào một nhóm.
     * Kiểm tra trạng thái hoạt động của nhóm và người dùng đã tham gia chưa.
     * Sử dụng syncWithoutDetaching để tránh trùng lặp.
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
     * Hàm leave(): Xử lý logic khi người dùng muốn rời khỏi nhóm.
     * Kiểm tra xem người dùng có thực sự trong nhóm không, sau đó dùng detach để xóa liên kết.
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
