<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('challenge_ai_tasks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('due_in_days');
            $table->string('proof_image_path')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('challenge_ai_tasks', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'proof_image_path']);
        });
    }
};
