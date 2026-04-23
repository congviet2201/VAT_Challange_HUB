<?php
/**
 * File purpose: app/Services/GoalAIService.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Services;

use RuntimeException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Dịch vụ tích hợp LM Studio để sinh sub-goals theo từng main goal cụ thể.
 *
 * Trách nhiệm chính:
 * - Chuẩn hóa đầu vào goal (title/description/duration)
 * - Gọi endpoint chat completions của LM Studio
 * - Parse và validate JSON sub-goals trước khi trả về controller
 */
/**
 * Lớp GoalAIService: mô tả vai trò chính của file.
 */
class GoalAIService
{
    /**
     * Sinh danh sách sub-goals từ AI cho một goal cụ thể.
     *
     * @param string|array $goalInput Chuỗi tự do hoặc mảng context goal.
     * @return array{sub_goals: array<int, array<string, mixed>>, raw_response: string}
     */
    /**
     * Hàm generateSubGoalsFromAI(): xử lý nghiệp vụ theo tên hàm.
     */
    public function generateSubGoalsFromAI(string|array $goalInput): array
    {
        // Tăng thời gian thực thi tối đa của PHP lên 5 phút (300 giây) 
        // để chờ Local AI sinh xong văn bản mà không bị sập
        set_time_limit(300);

        $goalContext = $this->normalizeGoalInput($goalInput);
        $prompt = $this->buildPrompt($goalContext);
        $baseUrl = rtrim((string) config('services.lmstudio.base_url', 'http://127.0.0.1:8000'), '/');
        $chatEndpoint = '/' . ltrim((string) config('services.lmstudio.chat_endpoint', '/v1/chat/completions'), '/');
        $requestUrl = $baseUrl . $chatEndpoint;
        $token = (string) config('services.lmstudio.api_key', 'sk-dummy-token-1234567890');
        $model = (string) config('services.lmstudio.model', 'local-model');
        $connectTimeout = max((int) config('services.lmstudio.connect_timeout', 10), 1);
        $timeout = max((int) config('services.lmstudio.timeout', 180), $connectTimeout);
        $host = (string) (parse_url($baseUrl, PHP_URL_HOST) ?: '');
        $port = (int) (parse_url($baseUrl, PHP_URL_PORT) ?: 80);
        
        try {
            $response = Http::withToken($token)
                ->connectTimeout($connectTimeout)
                ->timeout($timeout)
                ->post($requestUrl, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a helpful assistant. You must output ONLY a valid JSON array. No explanations, no markdown formatting.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.2,
                ]);
        } catch (Throwable $e) {
            Log::error('LM Studio transport error', [
                'url' => $requestUrl,
                'connect_timeout' => $connectTimeout,
                'timeout' => $timeout,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException($this->transportErrorMessage($requestUrl, $host, $port, $e));
        }

        if (! $response->successful()) {
            $errorBody = $response->body();
            $status = $response->status();
            Log::error('LM Studio API error', ['status' => $status, 'body' => $errorBody]);
            $msg = $this->lmStudioErrorMessage($errorBody, $status);
            throw new RuntimeException($msg);
        }

        $payload = $response->json();
        $lastRawContent = trim((string) data_get($payload, 'choices.0.message.content', ''));
        
        // Làm sạch JSON trong trường hợp AI trả về markdown code blocks
        $lastRawContent = preg_replace('/```json/i', '', $lastRawContent);
        $lastRawContent = preg_replace('/```/i', '', $lastRawContent);
        $lastRawContent = trim($lastRawContent);

        Log::info('LM Studio raw response', ['raw_response' => $lastRawContent]);

        $subGoals = $this->parseSubGoals($lastRawContent, $goalContext['duration_days']);
        if ($subGoals !== null) {
            return [
                'sub_goals' => $subGoals,
                'raw_response' => $lastRawContent,
            ];
        }

        Log::warning('Invalid JSON returned by LM Studio for sub-goals', [
            'raw_response' => $lastRawContent,
        ]);

        throw new RuntimeException('LM Studio returned invalid JSON: ' . $lastRawContent);
    }

    /**
     * Chuẩn hóa thông điệp lỗi từ LM Studio để hiển thị thân thiện hơn.
     */
    private function lmStudioErrorMessage(string $errorBody, int $status): string
    {
        $decoded = json_decode($errorBody, true);
        $inner = is_array($decoded) && isset($decoded['error']['message'])
            ? (string) $decoded['error']['message']
            : $errorBody;

        if (stripos($inner, 'No models loaded') !== false) {
            return
                'Trên server LM Studio chưa tải model. Hãy mở ứng dụng LM Studio tại máy ' .
                (parse_url((string) config('services.lmstudio.base_url', ''), PHP_URL_HOST) ?: 'server') .
                ' → tải (load) một model trong mục Developer / Local Server, rồi thử lại. ' .
                'Sau khi model đã chạy, có thể chỉnh LMSTUDIO_MODEL trong .env cho trùng tên model (xem /v1/models).';
        }

        if (stripos($inner, 'model') !== false && $status === 400) {
            return 'Yêu cầu tới LM Studio không hợp lệ (model). ' . $inner;
        }

        return 'LM Studio trả lỗi HTTP ' . $status . '. ' . $inner;
    }

    /**
     * Chuẩn hóa thông điệp lỗi khi không kết nối được tới LM Studio.
     */
    private function transportErrorMessage(string $requestUrl, string $host, int $port, Throwable $e): string
    {
        $message = 'Không thể kết nối đến LM Studio tại ' . $requestUrl .
            '. Hãy kiểm tra server LM Studio và cấu hình LMSTUDIO_BASE_URL/LMSTUDIO_CHAT_ENDPOINT.';

        if ($host === '127.0.0.1' && $port === 8000) {
            $message .= ' Cổng 8000 thường đang được Laravel dev server sử dụng; LM Studio mặc định thường chạy ở cổng 1234.';
        }

        if ($host !== '' && $host !== '127.0.0.1' && $host !== 'localhost') {
            $message .= ' Host hiện tại là máy từ xa (' . $host . '), nên máy chạy Laravel phải truy cập được địa chỉ này qua mạng LAN.';
        }

        $message .= ' Chi tiết: ' . $e->getMessage();

        return $message;
    }

    /**
     * Chuẩn hóa dữ liệu goal đầu vào, đảm bảo luôn có duration hợp lệ.
     */
    private function normalizeGoalInput(string|array $goalInput): array
    {
        if (is_string($goalInput)) {
            return [
                'title' => trim($goalInput),
                'description' => '',
                'duration_days' => 30,
            ];
        }

        return [
            'title' => trim((string) ($goalInput['title'] ?? '')),
            'description' => trim((string) ($goalInput['description'] ?? '')),
            'duration_days' => max(1, min(365, (int) ($goalInput['duration_days'] ?? 30))),
        ];
    }

    /**
     * Xây prompt ràng buộc sub-goals theo đúng mục tiêu chính hiện tại.
     */
    private function buildPrompt(array $goalContext): string
    {
        $title = $goalContext['title'] !== '' ? $goalContext['title'] : 'Mục tiêu chưa đặt tên';
        $description = $goalContext['description'] !== '' ? $goalContext['description'] : 'Không có mô tả thêm.';
        $durationDays = (int) $goalContext['duration_days'];

        return "You are a planning engine.
Create a detailed actionable plan ONLY for this single main goal:
- Main goal title: {$title}
- Main goal description: {$description}
- Main goal duration: {$durationDays} days

Strict rules:
1) Plan must be specific to this main goal only (no generic plan).
2) Every sub-goal must map to this duration window.
3) Return a JSON array only, no markdown and no explanation.
4) Each item must include: title, description, day.
5) day must be an integer between 1 and {$durationDays}.
6) Do not reference other goals or other users.

Output JSON only.";
    }

    /**
     * Parse và validate cấu trúc JSON AI trả về.
     */
    private function parseSubGoals(string $rawContent, int $durationDays): ?array
    {
        $decoded = json_decode($rawContent, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            return null;
        }

        $normalized = [];
        foreach ($decoded as $item) {
            if (
                ! is_array($item) ||
                ! isset($item['title'], $item['description'], $item['day'])
            ) {
                return null;
            }

            $day = (int) $item['day'];
            if ($day < 1 || $day > $durationDays) {
                return null;
            }

            $normalized[] = [
                'title' => (string) $item['title'],
                'description' => (string) $item['description'],
                'day' => $day,
            ];
        }

        return $normalized;
    }
}
