<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (!Schema::hasColumn('goals', 'status')) {
                // Sửa lại dòng này trong migration
$table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (Schema::hasColumn('goals', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};