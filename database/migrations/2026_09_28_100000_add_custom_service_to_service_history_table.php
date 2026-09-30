<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add custom_service_name column if not exists
        if (!Schema::hasColumn('service_history', 'custom_service_name')) {
            Schema::table('service_history', function (Blueprint $table) {
                $table->string('custom_service_name')->nullable()->after('category_id');
            });
        }

        // 2. Make category_id nullable so custom outside-system services can be saved
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE service_history MODIFY category_id BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_history', function (Blueprint $table) {
            if (Schema::hasColumn('service_history', 'custom_service_name')) {
                $table->dropColumn('custom_service_name');
            }
        });

        try {
            DB::statement('ALTER TABLE service_history MODIFY category_id BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
