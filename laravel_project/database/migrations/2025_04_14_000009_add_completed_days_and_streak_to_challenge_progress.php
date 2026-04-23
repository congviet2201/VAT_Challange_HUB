<?php
/**
 * File purpose: database/migrations/2025_04_14_000009_add_completed_days_and_streak_to_challenge_progress.php
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
        Schema::table('challenge_progress', function (Blueprint $table) {
            $table->integer('completed_days')->default(0)->after('progress');
            $table->integer('streak')->default(0)->after('completed_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_progress', function (Blueprint $table) {
            $table->dropColumn(['completed_days', 'streak']);
        });
    }
};
