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
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:purge-non-admin {--dry-run : Preview records that would be deleted}', function () {
    $adminIds = DB::table('users')
        ->where('role', 'admin')
        ->pluck('id');

    if ($adminIds->isEmpty()) {
        $this->error('Khong tim thay tai khoan admin. Da huy thao tac.');

        return self::FAILURE;
    }

    $userQuery = DB::table('users')->whereNotIn('id', $adminIds);
    $userCount = (clone $userQuery)->count();

    $sessionCount = DB::table('sessions')
        ->whereNotNull('user_id')
        ->whereNotIn('user_id', $adminIds)
        ->count();

    $this->info('Admin se duoc giu lai: ' . $adminIds->implode(', '));
    $this->line('So user khong phai admin: ' . $userCount);
    $this->line('So session lien quan se xoa: ' . $sessionCount);

    if ($this->option('dry-run')) {
        $this->comment('Dry-run xong. Chua co du lieu nao bi xoa.');

        return self::SUCCESS;
    }

    DB::transaction(function () use ($adminIds) {
        DB::table('sessions')
            ->whereNotNull('user_id')
            ->whereNotIn('user_id', $adminIds)
            ->delete();

        DB::table('users')
            ->whereNotIn('id', $adminIds)
            ->delete();
    });

    $remainingUsers = DB::table('users')
        ->select('id', 'name', 'email', 'role')
        ->orderBy('id')
        ->get();

    $this->info('Da xoa toan bo user khong phai admin.');
    foreach ($remainingUsers as $user) {
        $this->line($user->id . ' | ' . $user->name . ' | ' . $user->email . ' | ' . $user->role);
    }

    return self::SUCCESS;
})->purpose('Delete all users except admin accounts');
