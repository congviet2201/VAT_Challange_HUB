<?php
/**
 * File purpose: database/migrations/2026_04_23_190000_add_duration_days_to_goals_table.php
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
            $table->unsignedInteger('duration_days')->default(30)->after('description');
        });
    }

    /**
     * Hàm down(): xử lý nghiệp vụ theo tên hàm.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('duration_days');
        });
    }
};
