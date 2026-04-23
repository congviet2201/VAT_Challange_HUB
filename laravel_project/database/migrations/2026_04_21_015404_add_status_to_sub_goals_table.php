<?php
/**
 * File purpose: database/migrations/2026_04_21_015404_add_status_to_sub_goals_table.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
        Schema::table('sub_goals', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed'])->default('pending')->after('day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_goals', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
