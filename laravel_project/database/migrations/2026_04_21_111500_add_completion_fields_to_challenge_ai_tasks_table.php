<?php
/**
 * File purpose: database/migrations/2026_04_21_111500_add_completion_fields_to_challenge_ai_tasks_table.php
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
        Schema::table('challenge_ai_tasks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('due_in_days');
            $table->string('proof_image_path')->nullable()->after('completed_at');
        });
    }

    /**
     * Hàm down(): xử lý nghiệp vụ theo tên hàm.
     */
    public function down(): void
    {
        Schema::table('challenge_ai_tasks', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'proof_image_path']);
        });
    }
};
