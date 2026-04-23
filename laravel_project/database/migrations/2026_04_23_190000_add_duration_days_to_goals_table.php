<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('goals', 'duration_days')) {
            Schema::table('goals', function (Blueprint $table) {
                $table->unsignedInteger('duration_days')->default(30)->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('goals', 'duration_days')) {
            Schema::table('goals', function (Blueprint $table) {
                $table->dropColumn('duration_days');
            });
        }
    }
};
