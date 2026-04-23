<?php
/**
 * File purpose: database/migrations/2026_04_21_224500_fix_add_status_to_goals_for_sqlite.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (! Schema::hasColumn('goals', 'status')) {
            Schema::table('goals', function (Blueprint $table) {
                $table->string('status')->default('pending');
            });
        }
    }

    /**
     * Hàm down(): xử lý nghiệp vụ theo tên hàm.
     */
    public function down(): void
    {
        if (Schema::hasColumn('goals', 'status')) {
            Schema::table('goals', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
