<?php
/**
 * File purpose: app/Http/Controllers/GoalController.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
 * Điều phối toàn bộ luồng Mục tiêu chính (Goal) và Mục tiêu phụ (Sub-goal).
 *
 * Phụ thuộc chính:
 * - Eloquent models: Goal, SubGoal, SubGoalProof, Category
 * - Dịch vụ AI: GoalAIService
 * - Hạ tầng: DB transaction, Auth, Validation, Logging
 */
/**
 * Lá»›p GoalController: mĂ´ táº£ vai trĂ² chĂ­nh cá»§a file.
 */
class GoalController extends Controller
{
    /**
     * Hiển thị danh sách goal của người dùng hiện tại.
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
     * Trả về form tạo goal.
     */
    public function create()
    {
        $categories = Category::all(); // lấy dữ liệu
        return view('goals.create', compact('categories'));
    }

    /**
     * Lưu goal và sinh sub-goals bằng AI.
     *
     * Hỗ trợ cả 2 luồng:
     * - API: tạo 1 goal.
     * - Web form: tạo nhiều goal trong 1 request.
     */
    /**
     * HĂ m store(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
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

                    $goal = Goal::create([
                        'user_id' => Auth::id(),
                        'title' => $validated['title'],
                        'category_id' => $categoryId,
                        'description' => $validated['description'] ?? null,
                        'duration_days' => (int) ($validated['duration_days'] ?? 30),
                    ] + (Schema::hasColumn('goals', 'status') ? ['status' => 'pending'] : []));

                    $aiService = new GoalAIService();
                    $aiResult = $aiService->generateSubGoalsFromAI([
                        'title' => (string) $goal->title,
                        'description' => (string) ($goal->description ?? ''),
                        'duration_days' => (int) ($goal->duration_days ?? 30),
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
                    $createdGoal = Goal::create([
                        'user_id' => Auth::id(),
                        'title' => $goal['title'],
                        'category_id' => $goal['category_id'],
                        'description' => $goal['description'] ?? null,
                        'duration_days' => (int) ($goal['duration_days'] ?? 30),
                    ] + (Schema::hasColumn('goals', 'status') ? ['status' => 'pending'] : []));

                    $aiService = new GoalAIService();
                    $aiResult = $aiService->generateSubGoalsFromAI([
                        'title' => (string) $createdGoal->title,
                        'description' => (string) ($createdGoal->description ?? ''),
                        'duration_days' => (int) ($createdGoal->duration_days ?? 30),
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
     * Hiển thị chi tiết goal cùng danh sách sub-goals.
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
     * API sinh lại sub-goals cho một goal cụ thể.
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
     * API legacy: nộp proof trực tiếp từ GoalController.
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
     * API legacy: hoàn thành sub-goal trực tiếp từ GoalController.
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
     * Kiểm tra trạng thái hoàn thành của goal sau khi cập nhật sub-goal.
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
