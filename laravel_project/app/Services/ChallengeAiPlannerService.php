<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\ChallengeAiPlan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChallengeAiPlannerService
{
    public static function generatePlan(Challenge $challenge, int $userId, string $currentLevel): ChallengeAiPlan
    {
        try {
            if (config('services.openai.key') || env('OPENAI_API_KEY')) {
                $plan = self::createOpenAiPlan($challenge, $userId, $currentLevel);

                return self::persistPlan($challenge, $userId, $currentLevel, $plan, 'openai');
            }
        } catch (\Throwable $exception) {
            Log::error('Challenge AI planner failed, fallback to local plan', [
                'challenge_id' => $challenge->id,
                'user_id' => $userId,
                'error' => $exception->getMessage(),
            ]);
        }

        $fallbackPlan = self::buildFallbackPlan($challenge, $currentLevel);

        return self::persistPlan($challenge, $userId, $currentLevel, $fallbackPlan, 'fallback');
    }

    protected static function createOpenAiPlan(Challenge $challenge, int $userId, string $currentLevel): array
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
                    'content' => 'Bạn là AI coach học tập và phát triển kỹ năng. Hãy cá nhân hóa lộ trình ngắn hạn cho người dùng bằng tiếng Việt, rõ ràng, thực tế, không chung chung.',
                ],
                [
                    'role' => 'user',
                    'content' => self::buildPrompt($challenge, $currentLevel),
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 800,
        ];

        $response = Http::withToken($apiKey)
            ->accept('application/json')
            ->post('https://api.openai.com/v1/chat/completions', $payload);

        if ($response->failed()) {
            Log::error('OpenAI roadmap request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'challenge_id' => $challenge->id,
                'user_id' => $userId,
            ]);

            throw new \RuntimeException('Không thể kết nối tới OpenAI để tạo lộ trình AI.');
        }

        $content = $response->json('choices.0.message.content', '');
        $parsed = self::parseJsonResponse($content);

        if (!$parsed || empty($parsed['tasks']) || !is_array($parsed['tasks'])) {
            throw new \RuntimeException('OpenAI trả về lộ trình không đúng định dạng mong đợi.');
        }

        return [
            'summary' => (string) ($parsed['summary'] ?? 'Lộ trình AI được cá nhân hóa theo thông tin bạn đã cung cấp.'),
            'tasks' => self::normalizeTasks($parsed['tasks']),
        ];
    }

    protected static function persistPlan(
        Challenge $challenge,
        int $userId,
        string $currentLevel,
        array $plan,
        string $source
    ): ChallengeAiPlan {
        $aiPlan = ChallengeAiPlan::create([
            'user_id' => $userId,
            'challenge_id' => $challenge->id,
            'source' => $source,
            'current_level' => $currentLevel,
            'summary' => $plan['summary'] ?? null,
        ]);

        foreach (self::normalizeTasks($plan['tasks'] ?? []) as $task) {
            $aiPlan->tasks()->create($task);
        }

        return $aiPlan->load('tasks');
    }

    protected static function buildPrompt(Challenge $challenge, string $currentLevel): string
    {
        $categoryName = optional($challenge->category)->name ?? 'Danh mục không xác định';
        $dailyTime = (int) ($challenge->daily_time ?? 30);
        $difficulty = $challenge->difficulty ?? 'medium';

        return trim(<<<PROMPT
Hãy tạo một lộ trình AI cá nhân hóa bằng tiếng Việt cho người dùng.

Thông tin challenge:
- Tiêu đề: {$challenge->title}
- Mô tả: {$challenge->description}
- Danh mục: {$categoryName}
- Độ khó: {$difficulty}
- Thời gian người dùng nên dành mỗi ngày: {$dailyTime} phút

Thông tin người dùng:
- Trình độ hiện tại: {$currentLevel}

Yêu cầu:
1. Trả về duy nhất một JSON hợp lệ.
2. JSON gồm:
   - summary: tóm tắt ngắn 1-2 câu về chiến lược học/hoàn thành challenge.
   - tasks: mảng từ 5 đến 8 task cụ thể, dễ hành động ngay.
3. Mỗi task gồm:
   - title
   - description
   - order
   - estimated_minutes
   - due_in_days
4. Task phải cụ thể cho cá nhân mới bắt đầu hoặc trình độ thấp nếu người dùng nói họ mới biết căn bản.
5. Không viết giải thích ngoài JSON.
PROMPT);
    }

    protected static function parseJsonResponse(string $content): ?array
    {
        $json = trim($content);

        if ($json === '') {
            return null;
        }

        if (!str_starts_with($json, '{')) {
            preg_match('/\{.*\}/s', $json, $matches);
            $json = $matches[0] ?? $json;
        }

        $parsed = json_decode($json, true);

        return json_last_error() === JSON_ERROR_NONE ? $parsed : null;
    }

    protected static function normalizeTasks(array $tasks): array
    {
        $normalized = [];

        foreach (array_values($tasks) as $index => $task) {
            if (!is_array($task)) {
                continue;
            }

            $title = trim((string) ($task['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $normalized[] = [
                'order' => (int) ($task['order'] ?? ($index + 1)),
                'title' => $title,
                'description' => trim((string) ($task['description'] ?? '')),
                'estimated_minutes' => isset($task['estimated_minutes']) ? max(5, (int) $task['estimated_minutes']) : null,
                'due_in_days' => isset($task['due_in_days']) ? max(1, (int) $task['due_in_days']) : null,
            ];
        }

        return $normalized;
    }

    protected static function buildFallbackPlan(Challenge $challenge, string $currentLevel): array
    {
        $baseTime = max(10, (int) ($challenge->daily_time ?? 30));
        $categoryName = optional($challenge->category)->name ?? 'mục tiêu';
        $title = $challenge->title;

        return [
            'summary' => "Lộ trình này được cá nhân hóa từ challenge '{$title}' và trình độ hiện tại '{$currentLevel}'. Hãy tập trung vào các bước nhỏ, đều đặn trong 7 ngày đầu để tạo đà.",
            'tasks' => [
                [
                    'order' => 1,
                    'title' => "Đánh giá điểm xuất phát cho {$title}",
                    'description' => "Viết ngắn 3 điểm bạn đang làm tốt và 3 điểm còn yếu liên quan đến {$categoryName}.",
                    'estimated_minutes' => min($baseTime, 20),
                    'due_in_days' => 1,
                ],
                [
                    'order' => 2,
                    'title' => 'Ôn lại nền tảng cốt lõi',
                    'description' => 'Chọn một chủ đề căn bản nhất và dành một phiên học/tập trung không bị ngắt quãng.',
                    'estimated_minutes' => $baseTime,
                    'due_in_days' => 2,
                ],
                [
                    'order' => 3,
                    'title' => 'Làm một bài tập hoặc bài kiểm tra ngắn',
                    'description' => 'Thực hành ngay sau khi ôn nền tảng để phát hiện phần chưa hiểu rõ.',
                    'estimated_minutes' => $baseTime,
                    'due_in_days' => 3,
                ],
                [
                    'order' => 4,
                    'title' => 'Ghi chú lỗi sai phổ biến',
                    'description' => 'Tổng hợp những lỗi bạn vừa gặp và viết cách sửa cho từng lỗi.',
                    'estimated_minutes' => 15,
                    'due_in_days' => 4,
                ],
                [
                    'order' => 5,
                    'title' => 'Tăng nhẹ độ khó',
                    'description' => "Chọn một bài thực hành khó hơn mức hiện tại một chút để tiến gần mục tiêu '{$title}'.",
                    'estimated_minutes' => $baseTime + 10,
                    'due_in_days' => 6,
                ],
                [
                    'order' => 6,
                    'title' => 'Tự đánh giá sau 1 tuần',
                    'description' => 'So sánh với ngày đầu, xem phần nào đã tiến bộ và điều chỉnh cách học cho tuần tiếp theo.',
                    'estimated_minutes' => 20,
                    'due_in_days' => 7,
                ],
            ],
        ];
    }
}
