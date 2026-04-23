<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('goals', 'status')) {
            DB::statement(
                "ALTER TABLE `goals` MODIFY `status` ENUM('pending','in_progress','completed','verified') NOT NULL DEFAULT 'pending'"
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('goals', 'status')) {
            DB::statement(
                "ALTER TABLE `goals` MODIFY `status` ENUM('in_progress','completed','verified') NOT NULL DEFAULT 'in_progress'"
            );
        }
    }
};
