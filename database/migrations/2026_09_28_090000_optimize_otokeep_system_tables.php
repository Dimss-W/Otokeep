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
        // 1. Optimize users table: indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('notification_time');
            $table->index('role');
        });

        // 2. Optimize vehicles table: index category and add tax due dates
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('vehicle_category');
            $table->date('stnk_tax_due_date')->nullable()->after('current_km');
            $table->date('five_year_tax_due_date')->nullable()->after('stnk_tax_due_date');
        });

        // 3. Optimize recommendations table: add vehicle_category filter
        Schema::table('recommendations', function (Blueprint $table) {
            $table->string('vehicle_category')->default('all')->after('id'); // all, motor, mobil
            $table->index('vehicle_category');
        });

        // 4. Optimize service_categories table: add category and default intervals
        Schema::table('service_categories', function (Blueprint $table) {
            $table->string('vehicle_category')->default('all')->after('name'); // all, motor, mobil
            $table->unsignedInteger('default_interval_km')->default(2000)->after('vehicle_category');
            $table->unsignedInteger('default_interval_months')->default(3)->after('default_interval_km');
        });

        // 5. Optimize services table: add last_notified_at to prevent notification spam
        Schema::table('services', function (Blueprint $table) {
            $table->timestamp('last_notified_at')->nullable()->after('target_date');
            $table->index('target_km');
        });

        // 6. Optimize service_history table: add cost, workshop, notes, and composite index
        Schema::table('service_history', function (Blueprint $table) {
            $table->unsignedBigInteger('cost')->default(0)->after('service_km');
            $table->string('workshop_name')->nullable()->after('cost');
            $table->text('notes')->nullable()->after('workshop_name');
            $table->index(['vehicle_id', 'service_date']);
        });

        // 7. Create chat_messages table to persist Bang OTO AI conversations
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role'); // user, model
            $table->text('message');
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');

        Schema::table('service_history', function (Blueprint $table) {
            $table->dropIndex(['vehicle_id', 'service_date']);
            $table->dropColumn(['cost', 'workshop_name', 'notes']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['target_km']);
            $table->dropColumn('last_notified_at');
        });

        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropColumn(['vehicle_category', 'default_interval_km', 'default_interval_months']);
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropIndex(['vehicle_category']);
            $table->dropColumn('vehicle_category');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['vehicle_category']);
            $table->dropColumn(['stnk_tax_due_date', 'five_year_tax_due_date']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['notification_time']);
            $table->dropIndex(['role']);
        });
    }
};
