<?php
/**
 * File purpose: app/Http/Controllers/UserAdmin/NotificationController.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Http\Controllers\UserAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller quản lý thông báo của useradmin theo từng nhóm phụ trách.
 */
class NotificationController extends Controller
{
    // Hiển thị danh sách thông báo của UserAdmin
    /**
     * HĂ m index(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function index()
    {
        $auth = Auth::user();
        // Lấy thông báo do chính mình gửi
        $notifications = Notification::where('created_by', $auth->id)
            ->with('group')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('useradmin.notifications.index', compact('notifications'));
    }

    // Form gửi thông báo
    /**
     * HĂ m create(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function create()
    {
        $auth = Auth::user();
        // Chỉ có thể gửi thông báo tới các nhóm do chính mình tạo
        $groups = Group::where('created_by', $auth->id)
            ->where('is_active', 1)
            ->get();

        return view('useradmin.notifications.create', compact('groups'));
    }

    // Lưu thông báo
    /**
     * HĂ m store(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $group = Group::findOrFail($request->group_id);

        // Kiểm tra quyền sở hữu nhóm
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.notifications.index')
                ->with('error', 'Bạn không có quyền gửi thông báo tới nhóm này!');
        }

        Notification::create([
            'group_id' => $request->group_id,
            'created_by' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('useradmin.notifications.index')
            ->with('success', '✅ Gửi thông báo tới nhóm thành công!');
    }

    // Xem chi tiết thông báo
    /**
     * HĂ m show(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function show(Notification $notification)
    {
        if ($notification->created_by !== Auth::id()) {
            return redirect()->route('useradmin.notifications.index')
                ->with('error', 'Bạn không có quyền xem thông báo này!');
        }

        $groupMembers = $notification->group->users()->get();
        return view('useradmin.notifications.show', compact('notification', 'groupMembers'));
    }

    // Xóa thông báo
    /**
     * HĂ m destroy(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function destroy(Notification $notification)
    {
        if ($notification->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền xóa thông báo này!');
        }

        $title = $notification->title;
        $notification->delete();

        return redirect()->route('useradmin.notifications.index')
            ->with('success', "✅ Đã xóa thông báo \"$title\" thành công!");
    }
}
