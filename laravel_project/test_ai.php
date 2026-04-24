<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new App\Services\GoalAIService();
try {
    $result = $service->generateSubGoalsFromAI('Học 100 từ trong 5 ngày');
    echo 'SUCCESS: ' . count($result['sub_goals']) . ' sub-goals generated' . PHP_EOL;
    echo 'Raw response: ' . substr($result['raw_response'], 0, 200) . '...' . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
