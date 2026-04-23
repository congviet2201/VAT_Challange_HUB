<?php
/**
 * File purpose: database/migrations/2026_04_14_015756_create_group_challenge_table.php
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
        Schema::create('group_challenge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');
            $table->unique(['group_id', 'challenge_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_challenge');
    }
};
