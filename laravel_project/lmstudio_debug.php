<?php

$ch = curl_init('http://127.0.0.1:1234/v1/chat/completions');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer sk-dummy-token-1234567890',
]);
$data = [
    'model' => 'google/gemma-4-e4b',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are a JSON generator. Output ONLY a valid JSON array. No explanations, no markdown, no reasoning. Just the JSON array.',
        ],
        [
            'role' => 'user',
            'content' => 'Goal: Học 100 từ trong 5 ngày. Duration: 5 days. Description: học tiếng Anh.',
        ],
    ],
    'temperature' => 0.0,
    'max_tokens' => 200,
];
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$resp = curl_exec($ch);
if ($resp === false) {
    echo 'CURL ERROR: ' . curl_error($ch) . PHP_EOL;
} else {
    echo $resp . PHP_EOL;
}

curl_close($ch);
