<?php
/**
 * File purpose: database/migrations/2026_04_16_000001_create_challenge_feedback_histories_table.php
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
        Schema::create('challenge_feedback_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->string('source')->default('fallback');
            $table->text('evaluation');
            $table->json('suggestions');
            $table->timestamps();
        });
    }

    /**
     * Hàm down(): xử lý nghiệp vụ theo tên hàm.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_feedback_histories');
    }
};
