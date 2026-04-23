<?php
/**
 * File purpose: routes/console.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

/**
 * Khai báo các lệnh Artisan tùy biến chạy trong môi trường CLI.
 */
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
