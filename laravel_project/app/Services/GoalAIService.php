<?php

namespace App\Services;

use RuntimeException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoalAIService
{
    public function generateSubGoalsFromAI(string $userGoal): array
    {
        // Tăng thời gian thực thi tối đa của PHP lên 5 phút (300 giây) 
        // để chờ Local AI sinh xong văn bản mà không bị sập
        set_time_limit(300);

        $prompt = $this->buildPrompt($userGoal);
        $baseUrl = rtrim((string) config('services.lmstudio.base_url', 'http://127.0.0.1:8000'), '/');
        $chatEndpoint = '/' . ltrim((string) config('services.lmstudio.chat_endpoint', '/v1/chat/completions'), '/');
        $requestUrl = $baseUrl . $chatEndpoint;
        $token = (string) config('services.lmstudio.api_key', 'sk-dummy-token-1234567890');
        $model = (string) config('services.lmstudio.model', 'local-model');
        $connectTimeout = max((int) config('services.lmstudio.connect_timeout', 10), 1);
        $timeout = max((int) config('services.lmstudio.timeout', 180), $connectTimeout);
        
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
            throw new RuntimeException('LM Studio API request failed. HTTP ' . $status . ': ' . $errorBody);
        }

        $payload = $response->json();
        $lastRawContent = trim((string) data_get($payload, 'choices.0.message.content', ''));
        
        // Làm sạch JSON trong trường hợp AI trả về markdown code blocks
        $lastRawContent = preg_replace('/```json/i', '', $lastRawContent);
        $lastRawContent = preg_replace('/```/i', '', $lastRawContent);
        $lastRawContent = trim($lastRawContent);

        Log::info('LM Studio raw response', ['raw_response' => $lastRawContent]);

        $subGoals = $this->parseSubGoals($lastRawContent);
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

    private function buildPrompt(string $userGoal): string
    {
        return str_replace(
            '{USER_GOAL}',
            $userGoal,
            "Create a 30-day actionable plan for this goal: {USER_GOAL}.
Return ONLY a JSON array.
Each item must include:
- title
- description
- day (1 to 30)

No explanation.
No markdown.
Only JSON."
        );
    }

    private function parseSubGoals(string $rawContent): ?array
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
            if ($day < 1 || $day > 30) {
                return null;
            }

            $normalized[] = [
                'title' => (string) $item['title'],
                'description' => (string) $item['description'],
                'day' => $day,
            ];
        }

        return array_slice($normalized, 0, 30);
    }
}
