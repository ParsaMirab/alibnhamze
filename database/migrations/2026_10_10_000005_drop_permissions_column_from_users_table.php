<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the module specific `permissions` column if it was already applied by
     * the removed `2026_10_10_000004_add_permissions_to_users_table` migration.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'permissions')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('permissions');
            });
        }
    }

    /**
     * The custom permission system was removed, so there is nothing to restore.
     */
    public function down(): void
    {
        //
    }
};
