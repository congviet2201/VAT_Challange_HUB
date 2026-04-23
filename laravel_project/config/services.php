<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'verify_ssl' => env('OPENAI_VERIFY_SSL', true),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'verify_ssl' => env('GEMINI_VERIFY_SSL', true),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash-8b'),
        'fallback_models' => [
            'gemini-1.5-flash-8b',
            'gemini-1.5-flash',
            'gemini-1.5-pro',
        ],
    ],

    'lmstudio' => [
        'base_url' => env('LMSTUDIO_BASE_URL', 'http://127.0.0.1:1234'),
        'chat_endpoint' => env('LMSTUDIO_CHAT_ENDPOINT', '/v1/chat/completions'),
        'api_key' => env('LMSTUDIO_API_KEY', 'sk-dummy-token-1234567890'),
        'model' => env('LMSTUDIO_MODEL', 'local-model'),
        'connect_timeout' => (int) env('LMSTUDIO_CONNECT_TIMEOUT', 10),
        'timeout' => (int) env('LMSTUDIO_TIMEOUT', 180),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
