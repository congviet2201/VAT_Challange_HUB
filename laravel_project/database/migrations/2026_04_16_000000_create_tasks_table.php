<?php
/**
 * File purpose: database/migrations/2026_04_16_000000_create_tasks_table.php
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');
            $table->integer('order'); // Thứ tự task
            $table->string('title'); // Tiêu đề task
            $table->text('description')->nullable(); // Mô tả task
            $table->timestamps();
        });
    }

    /**
     * Hàm down(): xử lý nghiệp vụ theo tên hàm.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
