<?php
/**
 * File purpose: database/migrations/2026_04_21_220000_add_status_to_goals_table.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hàm up(): xử lý nghiệp vụ theo tên hàm.
     */
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (!Schema::hasColumn('goals', 'status')) {
                $table->enum('status', ['pending', 'completed'])->default('pending')->after('description');
            }
        });
    }

    /**
     * Hàm down(): xử lý nghiệp vụ theo tên hàm.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (Schema::hasColumn('goals', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
