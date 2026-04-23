<?php
/**
 * File purpose: app/Http/Controllers/UserAdmin/GroupController.php
 */

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
/**
 * Lớp GroupController: Controller này chịu trách nhiệm quản lý các nhóm do UserAdmin tạo ra.
 * Bao gồm các chức năng: Xem danh sách, tạo mới, chỉnh sửa, vô hiệu hóa nhóm,
 * cũng như thêm/xóa thành viên (User) và thêm/xóa các thử thách (Challenge) vào nhóm.
 */
class GroupController extends Controller
{
    /**
     * Hàm index(): Lấy và hiển thị danh sách các nhóm mà UserAdmin hiện tại đã tạo.
     * Chỉ lấy những nhóm có 'created_by' bằng với ID của UserAdmin đang đăng nhập.
     */
    public function index()
    {
        $auth = Auth::user();
        // Chỉ xem groups do chính mình tạo
        $groups = Group::where('created_by', $auth->id)->get();
        return view('useradmin.groups.index', compact('groups'));
    }

    /**
     * Hàm create(): Trả về giao diện (view) chứa form để UserAdmin tạo một nhóm mới.
     */
    public function create()
    {
        return view('useradmin.groups.create');
    }

    /**
     * Hàm store(): Xử lý dữ liệu từ form tạo nhóm.
     * Xác thực dữ liệu đầu vào (tên nhóm là bắt buộc), sau đó lưu vào cơ sở dữ liệu.
     * Mặc định nhóm mới sẽ được kích hoạt (is_active = 1) và gán 'created_by' là ID của người tạo.
     */
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

    /**
     * Hàm edit(): Hiển thị form chỉnh sửa thông tin nhóm.
     * Kiểm tra bảo mật: chỉ cho phép chỉnh sửa nếu nhóm đó do chính UserAdmin này tạo ra.
     * Nếu không có quyền, sẽ chuyển hướng về trang danh sách và báo lỗi.
     */
    public function edit(Group $group)
    {
        // Kiểm tra chủ sở hữu nhóm
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa nhóm này!');
        }

        return view('useradmin.groups.edit', compact('group'));
    }

    /**
     * Hàm update(): Xử lý dữ liệu từ form chỉnh sửa nhóm và cập nhật vào database.
     * Đồng thời cũng kiểm tra quyền sở hữu trước khi cho phép cập nhật.
     */
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

    /**
     * Hàm toggleStatus(): Bật hoặc tắt trạng thái hoạt động (is_active) của nhóm.
     * Giúp UserAdmin có thể tạm thời khóa một nhóm mà không cần xóa nó.
     * Cần kiểm tra quyền sở hữu trước khi thực hiện.
     */
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

    /**
     * Hàm addUserIndex(): Trả về giao diện hiển thị danh sách các người dùng (User)
     * hiện ĐANG KHÔNG nằm trong nhóm này, để UserAdmin có thể chọn và thêm họ vào nhóm.
     * Chỉ lấy các User có role là 'user' và đang hoạt động.
     */
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

    /**
     * Hàm addUser(): Xử lý việc thêm nhiều người dùng (Users) vào nhóm cùng lúc.
     * Dữ liệu nhận vào là một mảng ID của các người dùng (user_ids).
     * Hàm sẽ lặp qua mảng này và liên kết (attach) từng user vào nhóm trong bảng trung gian.
     */
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

    /**
     * Hàm show(): Hiển thị trang chi tiết của nhóm.
     * Trang này sẽ liệt kê danh sách tất cả các thành viên (members) hiện đang có trong nhóm.
     * Cần kiểm tra quyền sở hữu của UserAdmin trước khi xem.
     */
    public function show(Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền xem nhóm này!');
        }

        $members = $group->users()->get();
        return view('useradmin.groups.show', compact('group', 'members'));
    }

    /**
     * Hàm removeUser(): Xóa (rời) một người dùng cụ thể ra khỏi nhóm.
     * Thực hiện bằng cách gỡ bỏ liên kết (detach) giữa User và Group trong bảng trung gian.
     */
    public function removeUser(Group $group, User $user)
    {
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        $group->users()->detach($user->id);

        return back()->with('success', "✅ Đã xóa {$user->name} khỏi nhóm!");
    }

    /**
     * Hàm challengeIndex(): Lấy và hiển thị danh sách tất cả các thử thách (Challenges)
     * đã được gán cho nhóm này, kèm theo thông tin danh mục (Category) của từng thử thách.
     */
    public function challengeIndex(Group $group)
    {
        if ($group->created_by !== Auth::id()) {
            return redirect()->route('useradmin.groups.index')
                ->with('error', 'Bạn không có quyền xem nhóm này!');
        }

        $challenges = $group->challenges()->with('category')->get();
        return view('useradmin.groups.challenges', compact('group', 'challenges'));
    }

    /**
     * Hàm addChallengeIndex(): Trả về giao diện cho phép chọn thêm các thử thách mới vào nhóm.
     * Chỉ lấy danh sách các thử thách mà nhóm CHƯA có, sau đó nhóm chúng lại theo danh mục
     * để dễ dàng hiển thị trên giao diện (ví dụ: nhóm Sức khỏe, nhóm Học tập...).
     */
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

    /**
     * Hàm addChallenge(): Xử lý lưu các thử thách được chọn vào nhóm.
     * Nhận mảng ID của các thử thách và liên kết (attach) chúng vào nhóm thông qua bảng trung gian.
     */
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

    /**
     * Hàm removeChallenge(): Gỡ bỏ một thử thách khỏi nhóm.
     * Sử dụng detach để xóa liên kết giữa Challenge và Group trong bảng trung gian.
     */
    public function removeChallenge(Group $group, $challengeId)
    {
        if ($group->created_by !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền quản lý nhóm này!');
        }

        $group->challenges()->detach($challengeId);

        return back()->with('success', '✅ Đã xóa thử thách khỏi nhóm!');
    }
}
