<?php
/**
 * File purpose: database/migrations/2026_04_16_000001_create_challenge_feedback_histories_table.php
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
     * HĂ m down(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_feedback_histories');
    }
};
