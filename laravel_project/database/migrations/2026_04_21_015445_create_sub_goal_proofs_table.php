<?php
/**
 * File purpose: database/migrations/2026_04_21_015445_create_sub_goal_proofs_table.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_goal_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_goal_id')->constrained('sub_goals')->onDelete('cascade');
            $table->enum('type', ['image', 'text']);
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_goal_proofs');
    }
};
