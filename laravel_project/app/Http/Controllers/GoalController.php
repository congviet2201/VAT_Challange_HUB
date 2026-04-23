<?php
/**
 * Mục đích file: app/Http/Controllers/GoalController.php
 * Quản lý toàn bộ vòng đời của một Mục tiêu (Goal) và tự động hóa chia nhỏ mục tiêu bằng AI.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\SubGoal;
use App\Models\SubGoalProof;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Services\GoalAIService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Lớp GoalController: Chịu trách nhiệm xử lý toàn bộ các thao tác liên quan đến Mục tiêu lớn (Goal).
 * Bao gồm tạo mục tiêu mới, sử dụng AI để tự động sinh ra lộ trình gồm các mục tiêu phụ (SubGoals) theo từng ngày.
 * Quản lý tiến độ, nộp minh chứng hoàn thành cho các mục tiêu phụ và kiểm tra tổng kết mục tiêu.
 *
 * Phụ thuộc chính:
 * - Eloquent models: Goal, SubGoal, SubGoalProof, Category
 * - Dịch vụ AI: GoalAIService
 * - Hạ tầng: DB transaction, Auth, Validation, Logging
 */
class GoalController extends Controller
{
    /**
     * Hàm index(): Lấy và hiển thị danh sách toàn bộ các Mục tiêu (Goal) của người dùng hiện tại đang đăng nhập.
     * Dữ liệu được lấy kèm theo thông tin Danh mục (category) và các Mục tiêu phụ (subGoals).
     */
    private function buildGoalAttributes(array $goalInput): array
    {
        $attributes = [
            'user_id' => Auth::id(),
            'title' => $goalInput['title'],
            'category_id' => $goalInput['category_id'],
            'description' => $goalInput['description'] ?? null,
        ];

        if (Schema::hasColumn('goals', 'duration_days')) {
            $attributes['duration_days'] = (int) ($goalInput['duration_days'] ?? 30);
        }

        if (Schema::hasColumn('goals', 'status')) {
            $attributes['status'] = 'pending';
        }

        return $attributes;
    }

    /**
     * Hàm index(): Lấy và hiển thị danh sách toàn bộ các Mục tiêu (Goal) của người dùng hiện tại đang đăng nhập.
     * Dữ liệu được lấy kèm theo thông tin Danh mục (category) và các Mục tiêu phụ (subGoals).
     */
    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())
                     ->with('category', 'subGoals')
                     ->latest()
                     ->get();

        return view('goals.index', compact('goals'));
    }

    /**
     * Hàm create(): Trả về giao diện Form để người dùng tạo mới một Mục tiêu (Goal).
     * Lấy sẵn danh sách các Danh mục (Category) để hiển thị trong mục chọn loại mục tiêu.
     */
    public function create()
    {
        $categories = Category::all(); // lấy dữ liệu
        return view('goals.create', compact('categories'));
    }

    /**
     * Hàm store(): Lưu Mục tiêu mới do người dùng tạo và gọi AI để tạo Mục tiêu phụ.
     * Sử dụng Transaction để đảm bảo tính toàn vẹn: nếu gọi AI thất bại hoặc lưu lỗi thì quá trình tạo Mục tiêu cũng bị hủy (rollback).
     * Hỗ trợ hai luồng riêng biệt:
     * - Dành cho API: Tạo 1 mục tiêu, trả về chuỗi JSON thông tin.
     * - Dành cho Web Form: Hỗ trợ tạo nhiều mục tiêu cùng một lúc, gửi lại phản hồi kèm redirect giao diện.
     */
    public function store(Request $request)
    {
        $isApiRequest = $request->is('api/*') || $request->expectsJson();

        if ($isApiRequest) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'duration_days' => 'nullable|integer|min:1|max:365',
            ]);

            try {
                $result = DB::transaction(function () use ($validated, $request) {
                    $categoryId = $validated['category_id'] ?? Category::query()->value('id');
                    if (! $categoryId) {
                        throw new \RuntimeException('No category found for goal creation.');
                    }

                    $durationDays = (int) ($validated['duration_days'] ?? 30);

                    $goal = Goal::create($this->buildGoalAttributes([
                        'title' => $validated['title'],
                        'category_id' => $categoryId,
                        'description' => $validated['description'] ?? null,
                        'duration_days' => $durationDays,
                    ]));

                    $aiService = new GoalAIService();
                    $aiResult = $aiService->generateSubGoalsFromAI([
                        'title' => (string) $goal->title,
                        'description' => (string) ($goal->description ?? ''),
                        'duration_days' => (int) ($goal->duration_days ?? $durationDays),
                    ]);
                    $subGoalsData = $aiResult['sub_goals'];

                    foreach ($subGoalsData as $subGoalData) {
                        SubGoal::create([
                            'goal_id' => $goal->id,
                            'title' => $subGoalData['title'],
                            'description' => $subGoalData['description'],
                            'day' => $subGoalData['day'],
                            'status' => 'pending',
                        ]);
                    }

                    $response = [
                        'goal' => [
                            'id' => $goal->id,
                            'title' => $goal->title,
                        ],
                        'sub_goals' => $subGoalsData,
                    ];

                    if ($request->boolean('debug_ai')) {
                        $response['debug'] = [
                            'raw_ai_response' => $aiResult['raw_response'],
                        ];
                    }

                    return $response;
                });

                return response()->json($result, 201);
            } catch (Throwable $e) {
                Log::error('Failed to generate and save sub-goals from AI', [
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'message' => 'Failed to generate sub-goals from AI.',
                    'error' => app()->hasDebugModeEnabled() ? $e->getMessage() : null,
                ], 500);
            }
        }

        $request->validate([
            'goals' => 'required|array|min:1',
            'goals.*.title' => 'required|string',
            'goals.*.category_id' => 'required|exists:categories,id',
            'goals.*.duration_days' => 'required|integer|min:1|max:365',
        ]);

        try {
            $createdGoals = [];
            foreach ($request->goals as $goal) {
                $goalModel = DB::transaction(function () use ($goal) {
                    $durationDays = (int) ($goal['duration_days'] ?? 30);
                    $createdGoal = Goal::create($this->buildGoalAttributes([
                        'title' => $goal['title'],
                        'category_id' => $goal['category_id'],
                        'description' => $goal['description'] ?? null,
                        'duration_days' => $durationDays,
                    ]));

                    $aiService = new GoalAIService();
                    $aiResult = $aiService->generateSubGoalsFromAI([
                        'title' => (string) $createdGoal->title,
                        'description' => (string) ($createdGoal->description ?? ''),
                        'duration_days' => (int) ($createdGoal->duration_days ?? $durationDays),
                    ]);
                    $subGoalsData = $aiResult['sub_goals'];

                    foreach ($subGoalsData as $subGoalData) {
                        SubGoal::create([
                            'goal_id' => $createdGoal->id,
                            'title' => $subGoalData['title'],
                            'description' => $subGoalData['description'],
                            'day' => $subGoalData['day'],
                            'status' => 'pending',
                        ]);
                    }

                    return $createdGoal;
                });

                $createdGoals[] = $goalModel;
            }
        } catch (Throwable $e) {
            Log::error('Web goal creation failed during AI sub-goal generation', [
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'goal_ai' => app()->hasDebugModeEnabled()
                    ? 'Không thể tạo mục tiêu phụ từ AI: ' . $e->getMessage()
                    : 'Không thể tạo mục tiêu phụ từ AI. Vui lòng thử lại.',
            ]);
        }

        $firstGoal = $createdGoals[0] ?? null;
        if ($firstGoal) {
            return redirect()->route('goals.show', $firstGoal)->with('success', 'Đã tạo mục tiêu và generate sub-goals!');
        }

        return back()->with('success', 'Đã tạo mục tiêu và generate sub-goals!');
    }

    /**
     * Hàm show(): Hiển thị chi tiết của một Mục tiêu lớn cùng với danh sách các Mục tiêu phụ (Sub-goals) thuộc về nó.
     * Kiểm tra quyền sở hữu (người dùng chỉ được phép xem mục tiêu của chính mình tạo).
     */
    public function show(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        $subGoals = $goal->subGoals()->with('proofs')->orderBy('day')->get();

        return view('goals.show', compact('goal', 'subGoals'));
    }

    /**
     * Hàm generateSubGoals() (Dành cho API): Gọi lại AI để sinh mới/chạy lại danh sách Mục tiêu phụ (Sub-goals).
     * Thường được dùng khi có lỗi trước đó hoặc người dùng muốn tạo lại lộ trình phụ.
     */
    public function generateSubGoals(Request $request)
    {
        $request->validate([
            'goal_id' => 'required|exists:goals,id',
        ]);

        $goal = Goal::where('id', $request->goal_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $aiService = new GoalAIService();
        $aiResult = $aiService->generateSubGoalsFromAI([
            'title' => (string) $goal->title,
            'description' => (string) ($goal->description ?? ''),
            'duration_days' => (int) ($goal->duration_days ?? 30),
        ]);
        $subGoalsData = $aiResult['sub_goals'];

        if (empty($subGoalsData)) {
            return response()->json(['error' => 'Unable to generate sub-goals. Please try again.'], 500);
        }

        // Save sub-goals
        foreach ($subGoalsData as $subGoalData) {
            SubGoal::create([
                'goal_id' => $goal->id,
                'title' => $subGoalData['title'],
                'description' => $subGoalData['description'],
                'day' => $subGoalData['day'],
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'sub_goals' => $subGoalsData,
        ]);
    }

    /**
     * Hàm submitProof() (API legacy): Nhận dữ liệu nộp minh chứng (dạng hình ảnh hoặc văn bản)
     * từ người dùng cho một Mục tiêu phụ cụ thể. Sau khi validate, tạo mới một bản ghi SubGoalProof.
     */
    public function submitProof(Request $request)
    {
        $request->validate([
            'sub_goal_id' => 'required|exists:sub_goals,id',
            'type' => 'required|in:image,text',
            'content' => 'required|string',
        ]);

        $subGoal = SubGoal::where('id', $request->sub_goal_id)
                          ->whereHas('goal', function($q) {
                              $q->where('user_id', Auth::id());
                          })
                          ->firstOrFail();

        SubGoalProof::create([
            'sub_goal_id' => $subGoal->id,
            'type' => $request->type,
            'content' => $request->content,
        ]);

        return response()->json(['success' => true, 'message' => 'Proof submitted successfully']);
    }

    /**
     * Hàm completeSubGoal() (API legacy): Đánh dấu một Mục tiêu phụ là đã hoàn thành.
     * Yêu cầu bắt buộc là Mục tiêu phụ đó phải có ít nhất một minh chứng (proof) đã nộp.
     * Sau khi hoàn thành mục tiêu phụ, tiến hành kiểm tra luôn xem mục tiêu lớn đã hoàn thành chưa.
     */
    public function completeSubGoal(Request $request)
    {
        $request->validate([
            'sub_goal_id' => 'required|exists:sub_goals,id',
        ]);

        $subGoal = SubGoal::where('id', $request->sub_goal_id)
                          ->whereHas('goal', function($q) {
                              $q->where('user_id', Auth::id());
                          })
                          ->firstOrFail();

        if (! $subGoal->hasProof()) {
            return response()->json([
                'error' => 'Proof is required before completing this sub-goal.'
            ], 422);
        }

        $subGoal->update(['status' => 'completed']);

        // Check if goal is completed
        $subGoal->goal->checkCompletion();

        return response()->json(['success' => true, 'message' => 'Sub-goal completed']);
    }

    /**
     * Hàm checkGoalCompletion() (API): Kiểm tra lại trạng thái tổng thể của Mục tiêu lớn.
     * Tính toán xem tất cả các Mục tiêu phụ của nó đã hoàn thành hết chưa, nếu xong hết thì chuyển trạng thái Mục tiêu lớn thành 'completed'.
     */
    public function checkGoalCompletion(Request $request)
    {
        $request->validate([
            'goal_id' => 'required|exists:goals,id',
        ]);

        $goal = Goal::where('id', $request->goal_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $completed = $goal->checkCompletion();

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'status' => $goal->status
        ]);
    }
}
