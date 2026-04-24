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
        $goalContext = $this->normalizeGoalInput($goalInput);
        $prompt = $this->buildPrompt($goalContext);
        $baseUrl = rtrim((string) config('services.lmstudio.base_url', 'http://127.0.0.1:8000'), '/');
        $chatEndpoint = '/' . ltrim((string) config('services.lmstudio.chat_endpoint', '/v1/chat/completions'), '/');
        $requestUrl = $baseUrl . $chatEndpoint;
        $token = (string) config('services.lmstudio.api_key', 'sk-dummy-token-1234567890');
        $model = (string) config('services.lmstudio.model', 'local-model');
        $connectTimeout = max((int) config('services.lmstudio.connect_timeout', 10), 1);
        $timeout = max((int) config('services.lmstudio.timeout', 180), $connectTimeout);

        // Đặt thời gian chạy PHP lớn hơn timeout của Guzzle để tránh lỗi execution time exceeded.
        // Nếu LMSTUDIO_TIMEOUT=120 thì PHP sẽ được cho 150s để hoàn thành request.
        set_time_limit($timeout + 30);

        try {
            $response = Http::withToken($token)
                ->connectTimeout($connectTimeout)
                ->timeout($timeout)
                ->post($requestUrl, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a JSON generator. Output ONLY a valid JSON array. No explanations, no markdown, no reasoning. Just the JSON array.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.0,
                    'max_tokens' => 200,
                ]);
        } catch (Throwable $e) {
            Log::error('LM Studio transport error', [
                'url' => $requestUrl,
                'connect_timeout' => $connectTimeout,
                'timeout' => $timeout,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException(
                'Không thể kết nối đến LM Studio tại ' . $requestUrl .
                    '. Hãy kiểm tra server LM Studio và cấu hình LMSTUDIO_BASE_URL/LMSTUDIO_CHAT_ENDPOINT. Chi tiết: ' .
                    $e->getMessage()
            );
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
        if ($lastRawContent === '') {
            $lastRawContent = trim((string) data_get($payload, 'choices.0.message.reasoning_content', ''));
        }

        // Nếu AI trả thêm giải thích, hãy cố gắng trích JSON array từ nội dung
        $lastRawContent = $this->extractJsonString($lastRawContent);

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

        // Nếu vẫn không parse được, thử một lần nữa với phần text có JSON tiềm năng.
        $retryResult = $this->retryParseRawContent($lastRawContent, $goalContext['duration_days'], $requestUrl, $token, $model, $connectTimeout, $timeout);
        if ($retryResult !== null) {
            return [
                'sub_goals' => $retryResult,
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

        return "Goal: {$title}
Duration: {$durationDays} days
Description: {$description}

Return JSON: [{\"title\":\"Sub-goal 1\",\"description\":\"Description 1\",\"day\":1},{\"title\":\"Sub-goal 2\",\"description\":\"Description 2\",\"day\":2},{\"title\":\"Sub-goal 3\",\"description\":\"Description 3\",\"day\":3}]";
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

    /**
     * Trích JSON array từ nội dung text có thể chứa cả suy nghĩ/internal reasoning.
     */
    private function extractJsonString(string $text): string
    {
        if (trim($text) === '') {
            return $text;
        }

        $pattern = '/(\[\s*\{.*?\}\s*\])/s';
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }

        return $text;
    }

    /**
     * Thử parse lại raw response nếu lần đầu không trả về JSON hợp lệ.
     */
    private function retryParseRawContent(string $rawContent, int $durationDays, string $requestUrl, string $token, string $model, int $connectTimeout, int $timeout): ?array
    {
        if (trim($rawContent) === '') {
            return null;
        }

        $retryPrompt = "Extract the JSON array from the following text. Return ONLY the JSON array with objects containing title, description, day.\n\n" . $rawContent;

        try {
            $response = Http::withToken($token)
                ->connectTimeout($connectTimeout)
                ->timeout($timeout)
                ->post($requestUrl, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a JSON extractor. Return only the JSON array found in the user text. Do not add any explanation.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $retryPrompt,
                        ],
                    ],
                    'temperature' => 0.0,
                    'max_tokens' => 100,
                ]);
        } catch (Throwable $e) {
            Log::warning('LM Studio retry parse failed', [
                'error' => $e->getMessage(),
                'raw_content' => $rawContent,
            ]);
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();
        $retryRaw = trim((string) data_get($payload, 'choices.0.message.content', ''));
        if ($retryRaw === '') {
            $retryRaw = trim((string) data_get($payload, 'choices.0.message.reasoning_content', ''));
        }

        $retryRaw = $this->extractJsonString($retryRaw);
        $retryRaw = preg_replace('/```json/i', '', $retryRaw);
        $retryRaw = preg_replace('/```/i', '', $retryRaw);
        $retryRaw = trim($retryRaw);

        return $this->parseSubGoals($retryRaw, $durationDays);
    }
}
