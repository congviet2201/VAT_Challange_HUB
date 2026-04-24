<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GoalAIService
{
    public function generateSubGoalsFromAI(string|array $goalInput): array
    {
        $goalContext = $this->normalizeGoalInput($goalInput);
        $lmStudio = $this->resolveLmStudioConnection();
        $this->applyExecutionTimeLimit($lmStudio['connect_timeout'], $lmStudio['timeout']);

        $prompt = $this->buildPrompt($goalContext);
        $requestUrl = $lmStudio['request_url'];

        try {
            $response = $this->sendChatCompletion(
                $lmStudio,
                [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant. You must output ONLY a valid JSON array. No explanations, no markdown formatting.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                $this->maxTokensForDuration($goalContext['duration_days'])
            );
        } catch (Throwable $e) {
            Log::error('LM Studio transport error', [
                'url' => $requestUrl,
                'connect_timeout' => $lmStudio['connect_timeout'],
                'timeout' => $lmStudio['timeout'],
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException(
                'Khong the ket noi den LM Studio tai ' . $requestUrl .
                '. Hay mo LM Studio, bat Local Server, load model, roi thu lai. Chi tiet: ' .
                $e->getMessage()
            );
        }

        if (! $response->successful()) {
            $errorBody = $response->body();
            $status = $response->status();

            Log::error('LM Studio API error', [
                'status' => $status,
                'body' => $errorBody,
                'request_url' => $requestUrl,
                'model' => $lmStudio['model'],
            ]);

            if ($status === 400 && stripos($errorBody, 'model') !== false) {
                $retryConnection = $this->resolveLmStudioConnection(true, $lmStudio);

                if ($retryConnection['model'] !== $lmStudio['model']) {
                    $retryResponse = $this->sendChatCompletion(
                        $retryConnection,
                        [
                            [
                                'role' => 'system',
                                'content' => 'You are a helpful assistant. You must output ONLY a valid JSON array. No explanations, no markdown formatting.',
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt,
                            ],
                        ],
                        $this->maxTokensForDuration($goalContext['duration_days'])
                    );

                    if ($retryResponse->successful()) {
                        $response = $retryResponse;
                        $lmStudio = $retryConnection;
                        $requestUrl = $retryConnection['request_url'];
                    } else {
                        $errorBody = $retryResponse->body();
                        $status = $retryResponse->status();
                    }
                }
            }
        }

        if (! $response->successful()) {
            throw new RuntimeException($this->lmStudioErrorMessage(
                $response->body(),
                $response->status(),
                $lmStudio
            ));
        }

        $lastRawContent = $this->extractResponseContent($response);

        Log::info('LM Studio raw response', [
            'raw_response' => $lastRawContent,
            'request_url' => $requestUrl,
            'model' => $lmStudio['model'],
        ]);

        $subGoals = $this->parseSubGoals($lastRawContent, $goalContext['duration_days']);
        if ($subGoals !== null) {
            return [
                'sub_goals' => $subGoals,
                'raw_response' => $lastRawContent,
            ];
        }

        $repairedContent = $this->repairJsonWithModel($lmStudio, $lastRawContent, $goalContext['duration_days']);
        if ($repairedContent !== null) {
            Log::info('LM Studio malformed JSON repaired successfully', [
                'original_raw_response' => $lastRawContent,
                'repaired_response' => $repairedContent,
                'request_url' => $requestUrl,
                'model' => $lmStudio['model'],
            ]);

            return [
                'sub_goals' => $this->parseSubGoals($repairedContent, $goalContext['duration_days']) ?? [],
                'raw_response' => $repairedContent,
            ];
        }

        Log::warning('Invalid JSON returned by LM Studio for sub-goals', [
            'raw_response' => $lastRawContent,
            'request_url' => $requestUrl,
            'model' => $lmStudio['model'],
        ]);

        throw new RuntimeException('LM Studio returned invalid JSON: ' . $lastRawContent);
    }

    private function resolveLmStudioConnection(bool $refreshModel = false, array $knownConnection = []): array
    {
        $configuredBaseUrl = rtrim((string) config('services.lmstudio.base_url', 'http://127.0.0.1:8000'), '/');
        $configuredChatEndpoint = '/' . ltrim((string) config('services.lmstudio.chat_endpoint', '/v1/chat/completions'), '/');
        $configuredModel = trim((string) config('services.lmstudio.model', ''));
        $apiKey = (string) config('services.lmstudio.api_key', 'sk-dummy-token-1234567890');
        $connectTimeout = max((int) config('services.lmstudio.connect_timeout', 10), 1);
        $timeout = max((int) config('services.lmstudio.timeout', 180), $connectTimeout);
        $discoveryTimeout = min($timeout, max($connectTimeout + 5, 15));

        $baseUrls = $this->candidateBaseUrls($configuredBaseUrl);
        $chatEndpoints = $this->candidateChatEndpoints($configuredChatEndpoint);

        if ($knownConnection !== []) {
            $baseUrls = $this->prependUnique($knownConnection['base_url'] ?? null, $baseUrls);
            $chatEndpoints = $this->prependUnique($knownConnection['chat_endpoint'] ?? null, $chatEndpoints);
        }

        $lastErrors = [];

        foreach ($baseUrls as $baseUrl) {
            $discovered = $this->discoverLoadedModels($baseUrl, $apiKey, $connectTimeout, $discoveryTimeout);
            if ($discovered['ok']) {
                $selectedModel = $this->pickModel(
                    $configuredModel,
                    $discovered['models'],
                    $refreshModel ? null : ($knownConnection['model'] ?? null)
                );

                foreach ($chatEndpoints as $chatEndpoint) {
                    return [
                        'base_url' => $baseUrl,
                        'chat_endpoint' => $chatEndpoint,
                        'request_url' => $baseUrl . $chatEndpoint,
                        'api_key' => $apiKey,
                        'model' => $selectedModel,
                        'connect_timeout' => $connectTimeout,
                        'timeout' => $timeout,
                    ];
                }
            }

            if ($discovered['error'] !== null) {
                $lastErrors[] = $baseUrl . ' => ' . $discovered['error'];
            }
        }

        throw new RuntimeException(
            'Khong tim thay LM Studio dang san sang. Hay mo LM Studio, bat Local Server, load model, ' .
            'roi thu lai. Da thu: ' . implode(' | ', $lastErrors)
        );
    }

    private function discoverLoadedModels(string $baseUrl, string $apiKey, int $connectTimeout, int $timeout): array
    {
        $modelEndpoints = [
            '/v1/models',
            '/api/v1/models',
        ];

        foreach ($modelEndpoints as $modelEndpoint) {
            try {
                $response = $this->httpClient($apiKey, $connectTimeout, $timeout)
                    ->get($baseUrl . $modelEndpoint);
            } catch (Throwable $e) {
                return [
                    'ok' => false,
                    'models' => [],
                    'error' => $e->getMessage(),
                ];
            }

            if (! $response->successful()) {
                continue;
            }

            $models = $this->extractModelIds($response);
            if ($models !== []) {
                return [
                    'ok' => true,
                    'models' => $models,
                    'error' => null,
                ];
            }
        }

        return [
            'ok' => false,
            'models' => [],
            'error' => 'Khong lay duoc danh sach model tu /v1/models',
        ];
    }

    private function extractModelIds(Response $response): array
    {
        $data = $response->json('data');
        if (! is_array($data)) {
            return [];
        }

        $models = [];
        foreach ($data as $item) {
            $modelId = trim((string) data_get($item, 'id', ''));
            if ($modelId !== '') {
                $models[] = $modelId;
            }
        }

        return array_values(array_unique($models));
    }

    private function pickModel(string $configuredModel, array $availableModels, ?string $currentModel = null): string
    {
        $preferred = array_filter([
            $currentModel !== null ? trim($currentModel) : null,
            $configuredModel !== '' ? $configuredModel : null,
        ]);

        foreach ($preferred as $candidate) {
            if (in_array($candidate, $availableModels, true)) {
                return $candidate;
            }
        }

        if ($availableModels !== []) {
            $autoModel = $availableModels[0];

            if ($configuredModel !== '' && $configuredModel !== $autoModel) {
                Log::warning('Configured LM Studio model not loaded, using available model instead', [
                    'configured_model' => $configuredModel,
                    'selected_model' => $autoModel,
                    'available_models' => $availableModels,
                ]);
            }

            return $autoModel;
        }

        return $configuredModel !== '' ? $configuredModel : 'local-model';
    }

    private function candidateBaseUrls(string $configuredBaseUrl): array
    {
        return array_values(array_unique(array_filter([
            $configuredBaseUrl,
            'http://127.0.0.1:8000',
            'http://localhost:8000',
        ])));
    }

    private function candidateChatEndpoints(string $configuredChatEndpoint): array
    {
        return array_values(array_unique(array_filter([
            $configuredChatEndpoint,
            '/v1/chat/completions',
        ])));
    }

    private function prependUnique(?string $value, array $items): array
    {
        if ($value === null || $value === '') {
            return $items;
        }

        return array_values(array_unique(array_merge([$value], $items)));
    }

    private function httpClient(string $apiKey, int $connectTimeout, int $timeout): PendingRequest
    {
        return Http::withToken($apiKey)
            ->acceptJson()
            ->withOptions([
                'http_errors' => false,
                'curl' => [
                    CURLOPT_CONNECTTIMEOUT => $connectTimeout,
                    CURLOPT_TIMEOUT => $timeout,
                    CURLOPT_NOSIGNAL => 1,
                ],
            ])
            ->connectTimeout($connectTimeout)
            ->timeout($timeout);
    }

    private function sendChatCompletion(array $connection, array $messages, int $maxTokens): Response
    {
        return $this->httpClient(
            $connection['api_key'],
            $connection['connect_timeout'],
            $connection['timeout']
        )->post($connection['request_url'], [
            'model' => $connection['model'],
            'messages' => $messages,
            'temperature' => 0.2,
            'max_tokens' => $maxTokens,
        ]);
    }

    private function extractResponseContent(Response $response): string
    {
        $payload = $response->json();
        $content = trim((string) data_get($payload, 'choices.0.message.content', ''));
        $content = preg_replace('/```json/i', '', $content);
        $content = preg_replace('/```/i', '', $content);

        return trim($content);
    }

    private function applyExecutionTimeLimit(int $connectTimeout, int $timeout): void
    {
        $budget = max(30, min(240, $connectTimeout + $timeout + 15));

        @ini_set('max_execution_time', (string) $budget);

        if (function_exists('set_time_limit')) {
            @set_time_limit($budget);
        }
    }

    private function lmStudioErrorMessage(string $errorBody, int $status, array $connection): string
    {
        $decoded = json_decode($errorBody, true);
        $inner = is_array($decoded) && isset($decoded['error']['message'])
            ? (string) $decoded['error']['message']
            : trim($errorBody);

        if (stripos($inner, 'No models loaded') !== false) {
            return
                'LM Studio dang chay nhung chua load model. Hay mo LM Studio tai may ' .
                (parse_url((string) ($connection['base_url'] ?? ''), PHP_URL_HOST) ?: 'server') .
                ', vao Developer / Local Server, load model roi thu lai.';
        }

        if (stripos($inner, 'model') !== false && $status === 400) {
            return
                'Model hien tai khong hop le voi LM Studio. App da co co gang tu chon model dang load, ' .
                'nhung yeu cau van that bai. Chi tiet: ' . $inner;
        }

        return 'LM Studio tra loi HTTP ' . $status . '. ' . $inner;
    }

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

    private function buildPrompt(array $goalContext): string
    {
        $title = $goalContext['title'] !== '' ? $goalContext['title'] : 'Muc tieu chua dat ten';
        $description = $goalContext['description'] !== '' ? $goalContext['description'] : 'Khong co mo ta them.';
        $durationDays = (int) $goalContext['duration_days'];

        return "You are a planning engine.
Create a detailed actionable plan ONLY for this single main goal:
- Main goal title: {$title}
- Main goal description: {$description}
- Main goal duration: {$durationDays} days

Strict rules:
1) Plan must be specific to this main goal only (no generic plan).
2) The number of sub-goals must exactly equal the main goal duration in days.
3) Return a JSON array only, no markdown and no explanation.
4) Each item must include: title, description, day.
5) Return exactly {$durationDays} items.
6) day must be an integer and must follow the exact sequence 1, 2, 3 ... {$durationDays}, one item per day.
7) The description must be a plain-text bullet list with exactly 3 lines:
- Action: one specific action the user must do
- Metric: a measurable completion criterion
- Expected result: the concrete outcome expected after finishing
8) Each bullet line must be concise, practical, and directly tied to the main goal.
9) Each sub-goal must represent the work for its assigned day, so day 1 has the first task, day 2 has the second task, and so on until day {$durationDays}.
10) Do not reference other goals or other users.

Output JSON only.";
    }

    private function maxTokensForDuration(int $durationDays): int
    {
        return max(1200, min(12000, $durationDays * 140));
    }

    private function parseSubGoals(string $rawContent, int $durationDays): ?array
    {
        $decoded = $this->decodeSubGoalArray($rawContent);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            return null;
        }

        if (count($decoded) !== $durationDays) {
            return null;
        }

        $decoded = $this->assignSequentialDays($decoded);

        $normalized = [];
        foreach ($decoded as $item) {
            if (! is_array($item) || ! isset($item['title'], $item['description'])) {
                return null;
            }

            $day = (int) ($item['day'] ?? 0);
            if ($day < 1 || $day > $durationDays) {
                return null;
            }

            $normalized[] = [
                'title' => (string) $item['title'],
                'description' => $this->normalizeSubGoalDescription((string) $item['description']),
                'day' => $day,
            ];
        }

        return $normalized;
    }

    private function assignSequentialDays(array $items): array
    {
        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[$index]['day'] = $index + 1;
        }

        return $items;
    }

    private function normalizeSubGoalDescription(string $description): string
    {
        $trimmed = trim(str_replace(["\r\n", "\r"], "\n", $description));

        if ($trimmed === '') {
            return "- Action: Complete the assigned step.\n- Metric: Finish the task for the scheduled day.\n- Expected result: Make measurable progress toward the main goal.";
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $trimmed)), fn (string $line) => $line !== ''));
        $normalizedBulletLines = array_map(function (string $line): string {
            if (preg_match('/^[-*]\s+/', $line) === 1) {
                return preg_replace('/^[-*]\s+/', '- ', $line) ?? $line;
            }

            return $line;
        }, $lines);

        if (
            count($normalizedBulletLines) === 3
            && str_starts_with($normalizedBulletLines[0], '- ')
            && str_starts_with($normalizedBulletLines[1], '- ')
            && str_starts_with($normalizedBulletLines[2], '- ')
        ) {
            return implode("\n", $normalizedBulletLines);
        }

        return '- Action: ' . $trimmed . "\n"
            . '- Metric: Hoan thanh day du muc tieu cua moc nay.' . "\n"
            . '- Expected result: Tao tien do ro rang cho muc tieu chinh.';
    }

    private function decodeSubGoalArray(string $rawContent): ?array
    {
        $candidate = $this->extractJsonArrayCandidate($rawContent);
        $attempts = array_values(array_unique(array_filter([
            trim($rawContent),
            $candidate,
            $candidate !== null ? $this->repairMalformedJsonArray($candidate) : null,
        ], fn ($value) => is_string($value) && trim($value) !== '')));

        foreach ($attempts as $attempt) {
            $decoded = json_decode($attempt, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        $objects = $candidate !== null ? $this->extractCompleteObjectsFromArray($candidate) : [];
        if ($objects === []) {
            return null;
        }

        $decodedObjects = [];
        foreach ($objects as $objectJson) {
            $decodedObject = json_decode($objectJson, true);
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decodedObject)) {
                return null;
            }

            $decodedObjects[] = $decodedObject;
        }

        return $decodedObjects;
    }

    private function extractJsonArrayCandidate(string $rawContent): ?string
    {
        $start = strpos($rawContent, '[');
        if ($start === false) {
            return null;
        }

        $end = strrpos($rawContent, ']');
        if ($end === false || $end < $start) {
            return trim(substr($rawContent, $start));
        }

        return trim(substr($rawContent, $start, $end - $start + 1));
    }

    private function repairMalformedJsonArray(string $candidate): string
    {
        $repaired = trim($candidate);
        $repaired = preg_replace('/,\s*([\]}])/', '$1', $repaired) ?? $repaired;

        if (! str_starts_with($repaired, '[')) {
            $repaired = '[' . ltrim($repaired, ',');
        }

        $openSquare = substr_count($repaired, '[');
        $closeSquare = substr_count($repaired, ']');
        if ($closeSquare < $openSquare) {
            $repaired .= str_repeat(']', $openSquare - $closeSquare);
        }

        $openCurly = substr_count($repaired, '{');
        $closeCurly = substr_count($repaired, '}');
        if ($closeCurly < $openCurly) {
            $repaired .= str_repeat('}', $openCurly - $closeCurly);
        }

        return $repaired;
    }

    private function extractCompleteObjectsFromArray(string $candidate): array
    {
        $objects = [];
        $buffer = '';
        $depth = 0;
        $inString = false;
        $escapeNext = false;

        $length = strlen($candidate);
        for ($index = 0; $index < $length; $index++) {
            $char = $candidate[$index];

            if ($depth > 0) {
                $buffer .= $char;
            }

            if ($escapeNext) {
                $escapeNext = false;
                continue;
            }

            if ($char === '\\' && $inString) {
                $escapeNext = true;
                continue;
            }

            if ($char === '"') {
                $inString = ! $inString;
                continue;
            }

            if ($inString) {
                continue;
            }

            if ($char === '{') {
                if ($depth === 0) {
                    $buffer = '{';
                }
                $depth++;
                continue;
            }

            if ($char === '}' && $depth > 0) {
                $depth--;
                if ($depth === 0) {
                    $objects[] = $buffer;
                    $buffer = '';
                }
            }
        }

        return $objects;
    }

    private function repairJsonWithModel(array $connection, string $rawContent, int $durationDays): ?string
    {
        if (trim($rawContent) === '') {
            return null;
        }

        try {
            $response = $this->sendChatCompletion(
                $connection,
                [
                    [
                        'role' => 'system',
                        'content' => 'You repair malformed JSON. Return ONLY a valid JSON array. Keep complete items, discard incomplete trailing fragments, and ensure each item includes title and description. Add a valid integer day if it is missing.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Fix this malformed JSON array for a {$durationDays}-day goal. Keep only valid items. If a day is missing, infer a sensible integer day between 1 and {$durationDays} based on item order.\n\nMalformed JSON:\n{$rawContent}",
                    ],
                ],
                500
            );
        } catch (Throwable $e) {
            Log::warning('LM Studio JSON repair transport error', [
                'request_url' => $connection['request_url'] ?? null,
                'model' => $connection['model'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return null;
        }

        if (! $response->successful()) {
            Log::warning('LM Studio JSON repair request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'request_url' => $connection['request_url'] ?? null,
                'model' => $connection['model'] ?? null,
            ]);

            return null;
        }

        $content = $this->extractResponseContent($response);

        return $this->parseSubGoals($content, $durationDays) !== null ? $content : null;
    }
}
