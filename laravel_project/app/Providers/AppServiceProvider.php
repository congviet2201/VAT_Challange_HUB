<?php
/**
 * Mục đích file: app/Providers/AppServiceProvider.php
 * Khởi tạo và đăng ký các dịch vụ (services) dùng chung cho toàn bộ ứng dụng.
 */

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * Lớp AppServiceProvider: Cấu hình mặc định cho các thành phần cốt lõi của Laravel.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Hàm register(): Đăng ký bất kỳ dịch vụ (application services) nào vào service container của Laravel.
     * Thường dùng để liên kết (bind) các interfaces với các lớp thực thi (implementations) cụ thể.
     */
    public function register(): void
    {
        // Nơi đăng ký các class, service chưa cần khởi chạy ngay lập tức.
    }

    /**
     * Hàm boot(): Thực thi sau khi tất cả các services đã được đăng ký thành công.
     * Dùng để khởi tạo cấu hình các thành phần của ứng dụng (Ví dụ: view composer, custom validation, paginator,...).
     */
    public function boot(): void
    {
        // Cấu hình thư viện phân trang (Paginator) sử dụng giao diện của Bootstrap 5 thay vì mặc định (Tailwind).
        Paginator::useBootstrapFive();
    }
}
