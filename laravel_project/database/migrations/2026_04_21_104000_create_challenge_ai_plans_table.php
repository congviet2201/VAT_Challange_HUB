<?php
/**
 * File purpose: database/migrations/2026_04_21_104000_create_challenge_ai_plans_table.php
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
        Schema::create('challenge_ai_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->string('source')->default('fallback');
            $table->text('current_level');
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * HĂ m down(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_ai_plans');
    }
};
