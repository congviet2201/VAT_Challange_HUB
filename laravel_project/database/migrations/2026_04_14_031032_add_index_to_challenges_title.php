<?php
/**
 * File purpose: database/migrations/2026_04_14_031032_add_index_to_challenges_title.php
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
        Schema::table('challenges', function (Blueprint $table) {
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropIndex(['title']);
        });
    }
};
