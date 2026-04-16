<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\ChallengeProgress;
use App\Services\OpenAiFeedbackService;
use Illuminate\Support\Facades\Log;

/**
 * ChallengeFeedbackService - Tạo đánh giá và gợi ý dựa trên tiến độ thử thách.
 *
 * Nếu cấu hình OpenAI có sẵn, sẽ gọi AI thật sự. Nếu không, sẽ sử dụng
 * bộ quy tắc "AI-like" nội bộ để giữ tính năng hoạt động ổn định.
 */
class ChallengeFeedbackService
{
    /**
     * Tạo phản hồi đánh giá và gợi ý cho người dùng.
     *
     * @param ChallengeProgress $progress
     * @param Challenge $challenge
     * @return array
     */
    public static function getFeedback(ChallengeProgress $progress, Challenge $challenge): array
    {
        try {
            if (config('services.openai.key') || env('OPENAI_API_KEY')) {
                $feedback = OpenAiFeedbackService::generateFeedback($progress, $challenge);

                return array_merge($feedback, [
                    'source' => 'openai',
                ]);
            }
        } catch (\Throwable $exception) {
            Log::error('OpenAI feedback failed, fallback to local AI-like rules', [
                'error' => $exception->getMessage(),
            ]);
        }

        $fallback = self::buildFallbackFeedback($progress, $challenge);

        return array_merge($fallback, [
            'source' => 'fallback',
        ]);
    }

    protected static function buildFallbackFeedback(ChallengeProgress $progress, Challenge $challenge): array
    {
        $completion = (int) $progress->progress;
        $streak = (int) $progress->streak;
        $completedDays = (int) $progress->completed_days;
        $difficulty = $challenge->difficulty;
        $categoryName = optional($challenge->category)->name ?? 'thử thách';
        $title = $challenge->title;
        $totalDays = $challenge->duration_days ?? 30;

        $evaluation = self::buildEvaluation($completion, $streak, $difficulty, $categoryName, $title);
        $suggestions = self::buildSuggestions($completion, $streak, $completedDays, $difficulty, $totalDays, $categoryName, $title);

        return [
            'evaluation' => $evaluation,
            'suggestions' => $suggestions,
        ];
    }

    protected static function buildEvaluation(int $completion, int $streak, string $difficulty, string $categoryName, string $title): string
    {
        if ($completion >= 100) {
            return "AI Coach: Bạn đã hoàn thành thử thách '$title' thuộc danh mục '$categoryName' rất xuất sắc. Giữ vững nhịp độ và chuẩn bị cho thử thách tiếp theo!";
        }

        if ($completion >= 75) {
            $prefix = "AI Coach: Tiến độ của bạn với thử thách '$title' đang rất tốt.";
            if ($difficulty === 'hard') {
                return $prefix . ' Thử thách khó cần bạn giữ vững tinh thần đến cuối cùng.';
            }
            if ($difficulty === 'medium') {
                return $prefix . ' Giữ đều nhịp để hoàn thành mục tiêu vừa sức này.';
            }

            return $prefix . ' Đây là một thử thách dễ và bạn đã tiến rất nhanh.';
        }

        if ($completion >= 50) {
            if ($difficulty === 'hard') {
                return "AI Coach: Bạn đã qua nửa chặng đường cho thử thách khó '$title'. Hãy tiếp tục duy trì tinh thần và không bỏ cuộc.";
            }

            return "AI Coach: Bạn đang đi đúng hướng với thử thách '$title'. Một vài chuỗi check-in liên tiếp sẽ giúp bạn bền bỉ hơn.";
        }

        if ($completion >= 25) {
            return "AI Coach: Đây là khởi đầu tích cực cho thử thách '$title' thuộc '$categoryName'. Cố gắng duy trì ít nhất " . max(1, $streak) . " ngày liên tiếp để xây thói quen.";
        }

        if ($difficulty === 'hard') {
            return "AI Coach: Thử thách '$title' có thể khó, nhưng mỗi bước nhỏ hôm nay đều là tiến bộ đáng giá.";
        }

        return "AI Coach: Hãy bắt đầu từng bước một với thử thách '$title'. Mỗi ngày check-in là một bước tiến quan trọng.";
    }

    protected static function buildSuggestions(int $completion, int $streak, int $completedDays, string $difficulty, int $totalDays, string $categoryName, string $title): array
    {
        $suggestions = [];

        if ($completion >= 100) {
            $suggestions[] = "Chúc mừng! Thử thách '$title' thuộc '$categoryName' đã hoàn thành.";
            $suggestions[] = 'Hãy cân nhắc chọn thử thách tiếp theo phù hợp với mức năng lượng hiện tại của bạn.';
            return $suggestions;
        }

        if ($completion < 25) {
            $suggestions[] = 'Thử chia mục tiêu thành phần nhỏ hơn và hoàn thành từng bước.';
            $suggestions[] = 'Nếu hôm nay chưa đạt được, hãy tiếp tục lại vào ngày mai và giữ tinh thần tích cực.';
            if ($difficulty === 'hard') {
                $suggestions[] = 'Vì thử thách khó, bạn có thể bắt đầu từ phiên ngắn hơn rồi tăng dần.';
            }
        }

        if ($completion >= 25 && $completion < 50) {
            $suggestions[] = 'Duy trì ít nhất 3 - 5 ngày liên tiếp để hình thành thói quen.';
            $suggestions[] = 'Ghi lại việc hoàn thành hàng ngày để nhìn thấy tiến bộ.';
            if ($categoryName === 'Sức khỏe') {
                $suggestions[] = 'Cân nhắc kết hợp thói quen này vào buổi sáng để cơ thể quen dần.';
            }
        }

        if ($completion >= 50 && $completion < 75) {
            $suggestions[] = 'Bạn đã nửa chặng đường. Giữ vững nhịp để không gián đoạn streak.';
            $suggestions[] = 'Kiểm tra lại mục tiêu mỗi sáng và nhớ lý do bạn bắt đầu.';
            if ($difficulty === 'easy') {
                $suggestions[] = 'Vì thử thách dễ, bạn có thể tăng mức độ tập trung để đạt hiệu quả hơn.';
            }
        }

        if ($completion >= 75 && $completion < 100) {
            $suggestions[] = 'Bạn sắp về đích. Tập trung vào việc hoàn thành những ngày cuối cùng.';
            $suggestions[] = 'Nếu gặp khó khăn, chia nhỏ nhiệm vụ còn lại để dễ tiếp tục hơn.';
            if ($difficulty === 'hard') {
                $suggestions[] = 'Đối với thử thách khó, nghỉ ngơi ngắn trước khi tiếp tục sẽ giúp bạn duy trì ổn định.';
            }
        }

        if ($difficulty === 'hard' && $completion < 75) {
            $suggestions[] = 'Vì thử thách khó, hãy nghỉ ngơi đúng cách và phục hồi năng lượng sau mỗi phiên làm việc.';
        }

        if ($streak >= 3) {
            $suggestions[] = 'Bạn đã giữ được chuỗi ' . $streak . ' ngày. Hãy tiếp tục giữ phong độ này!';
        }

        if ($completedDays > 0 && $completion < 100) {
            $suggestions[] = 'Bạn đã hoàn thành ' . $completedDays . ' ngày. Một vài ngày nữa sẽ giúp bạn về đích.';
        }

        $remainingDays = max(0, $totalDays - $completedDays);
        if ($remainingDays > 0 && $completion < 100) {
            $suggestions[] = 'Còn khoảng ' . $remainingDays . ' ngày nữa để hoàn thành thử thách nếu bạn giữ đều đặn.';
        }

        if ($categoryName === 'Học tập') {
            $suggestions[] = 'Dành thời gian ôn lại kiến thức ngắn trước khi bắt đầu mỗi phiên để ghi nhớ tốt hơn.';
        }

        return array_values(array_unique($suggestions));
    }
}
