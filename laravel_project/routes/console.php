<?php

/**
 * Khai báo các lệnh Artisan tùy biến chạy trong môi trường CLI.
 */
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
