<?php
/**
 * Mục đích file: app/Http/Controllers/UserAdmin/NotificationController.php
 * Quản lý việc soạn và gửi thông báo của quản trị viên (UserAdmin) tới các nhóm mà họ quản lý.
 */

namespace App\Http\Controllers\UserAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Lớp NotificationController: Chịu trách nhiệm quản lý thông báo của UserAdmin theo từng nhóm phụ trách.
 * Cung cấp các tính năng: xem danh sách thông báo đã gửi, gửi thông báo mới cho nhóm, xem chi tiết và xóa thông báo.
 */
class NotificationController extends Controller
{
    /**
     * Hàm index(): Lấy danh sách các thông báo do chính UserAdmin hiện tại đã tạo ra,
     * kèm theo thông tin của nhóm nhận thông báo, sắp xếp theo thời gian mới nhất.
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

    /**
     * Hàm create(): Trả về form để UserAdmin soạn và gửi thông báo mới.
     * Chỉ lấy danh sách các nhóm đang hoạt động và do chính UserAdmin này quản lý để hiển thị vào thẻ select.
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

    /**
     * Hàm store(): Nhận dữ liệu từ form, kiểm tra quyền sở hữu đối với nhóm được chọn,
     * và lưu thông báo mới vào cơ sở dữ liệu.
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

    /**
     * Hàm show(): Hiển thị nội dung chi tiết của một thông báo cụ thể.
     * Kèm theo hiển thị danh sách các thành viên trong nhóm nhận thông báo đó.
     * Cần kiểm tra quyền sở hữu thông báo trước khi cho phép xem.
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

    /**
     * Hàm destroy(): Xóa bỏ một thông báo khỏi hệ thống.
     * Chỉ người tạo thông báo mới có quyền thực hiện chức năng này.
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
