<?php
/**
 * File purpose: database/migrations/2026_04_21_224500_fix_add_status_to_goals_for_sqlite.php
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
        if (! Schema::hasColumn('goals', 'status')) {
            Schema::table('goals', function (Blueprint $table) {
                $table->string('status')->default('pending');
            });
        }
    }

    /**
     * HĂ m down(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
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
