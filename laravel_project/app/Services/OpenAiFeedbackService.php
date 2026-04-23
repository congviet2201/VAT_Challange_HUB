<?php
/**
 * File purpose: app/Services/OpenAiFeedbackService.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Services;

use App\Models\Challenge;
use App\Models\ChallengeProgress;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OpenAiFeedbackService - Gọi OpenAI để tạo phản hồi AI thật.
 */
class OpenAiFeedbackService
{
    public static function generateFeedback(ChallengeProgress $progress, Challenge $challenge): array
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');

        if (empty($apiKey)) {
            throw new \RuntimeException('OPENAI_API_KEY chưa được cấu hình.');
        }

        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Bạn là một trợ lý AI coach chuyên đưa ra đánh giá ngắn gọn và gợi ý hữu ích cho người dùng đang tham gia thử thách.'
                ],
                [
                    'role' => 'user',
                    'content' => self::buildPrompt($progress, $challenge),
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 300,
        ];

        $response = Http::withToken($apiKey)
            ->accept('application/json')
            ->post('https://api.openai.com/v1/chat/completions', $payload);

        if ($response->failed()) {
            Log::error('OpenAI request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Không thể kết nối tới OpenAI để tạo phản hồi AI.');
        }

        $content = $response->json('choices.0.message.content', '');
        $parsed = self::parseJsonResponse($content);

        if (!$parsed || empty($parsed['evaluation']) || empty($parsed['suggestions'])) {
            throw new \RuntimeException('OpenAI trả về dữ liệu không đúng định dạng JSON mong đợi.');
        }

        return [
            'evaluation' => $parsed['evaluation'],
            'suggestions' => (array) $parsed['suggestions'],
        ];
    }

    protected static function buildPrompt(ChallengeProgress $progress, Challenge $challenge): string
    {
        $categoryName = optional($challenge->category)->name ?? 'Danh mục không xác định';
        $totalDays = $challenge->duration_days ?? 30;
        $startedAt = $progress->started_at ? $progress->started_at->format('d/m/Y') : 'Không rõ';

        return trim(<<<PROMPT
Hãy giúp tôi đóng vai một AI coach bằng tiếng Việt.

Thông tin thử thách:
- Tiêu đề: {$challenge->title}
- Danh mục: {$categoryName}
- Độ khó: {$challenge->difficulty}
- Thời gian mỗi ngày: {$challenge->daily_time} phút
- Tổng số ngày dự kiến: {$totalDays}

Tiến độ người dùng:
- Hoàn thành: {$progress->progress}%
- Số ngày đã hoàn thành: {$progress->completed_days}
- Chuỗi liên tiếp: {$progress->streak} ngày
- Bắt đầu từ: {$startedAt}

Yêu cầu:
1. Trả về một đối tượng JSON hợp lệ với hai trường:
   - evaluation: một câu đánh giá ngắn gọn, động lực.
   - suggestions: một mảng các gợi ý cụ thể để người dùng hoàn thành thử thách.
2. Chỉ xuất ra JSON, không thêm văn bản giải thích ngoài JSON.
3. Tiếng Việt rõ ràng, thân thiện.
PROMPT);
    }

    protected static function parseJsonResponse(string $content): ?array
    {
        $json = trim($content);

        if (empty($json)) {
            return null;
        }

        if (!str_starts_with($json, '{')) {
            preg_match('/\{.*\}/s', $json, $matches);
            $json = $matches[0] ?? $json;
        }

        $parsed = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $parsed;
    }
}
