<?php
/**
 * File purpose: database/migrations/2025_04_07_000007_drop_challenge_checkins_table.php
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
        Schema::dropIfExists('challenge_checkins');
    }

    /**
     * HĂ m down(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function down(): void
    {
        //
    }
};
