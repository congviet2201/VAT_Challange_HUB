<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (! Schema::hasColumn('goals', 'duration_days')) {
                $table->unsignedInteger('duration_days')->default(30)->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (Schema::hasColumn('goals', 'duration_days')) {
                $table->dropColumn('duration_days');
            }
        });
    }
};
