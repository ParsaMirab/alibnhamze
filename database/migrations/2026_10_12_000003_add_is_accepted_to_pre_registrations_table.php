<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The `is_accepted` column is part of the original create migration, but
     * databases that were migrated before the field was added are missing it.
     * This migration adds it only when needed (and is a no-op on fresh installs)
     * so existing records are preserved and nothing is duplicated.
     */
    public function up(): void
    {
        if (Schema::hasColumn('pre_registrations', 'is_accepted')) {
            return;
        }

        Schema::table('pre_registrations', function (Blueprint $table) {
            // null = pending, true = approved, false = rejected.
            $table->boolean('is_accepted')->nullable()->default(null)->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('pre_registrations', 'is_accepted')) {
            return;
        }

        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn('is_accepted');
        });
    }
};
