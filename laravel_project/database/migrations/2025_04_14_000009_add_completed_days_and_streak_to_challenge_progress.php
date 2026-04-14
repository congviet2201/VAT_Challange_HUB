<?php

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
