<?php
/**
 * File purpose: database/migrations/2026_04_21_104100_create_challenge_ai_tasks_table.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * HĂ m up(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function up(): void
    {
        Schema::create('challenge_ai_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_ai_plan_id')->constrained('challenge_ai_plans')->cascadeOnDelete();
            $table->unsignedInteger('order')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('estimated_minutes')->nullable();
            $table->unsignedInteger('due_in_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * HĂ m down(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_ai_tasks');
    }
};
