<?php

use App\Enums\OtpType;
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
        Schema::table('otps', function (Blueprint $table) {
            // Existing rows keep the `login` default so authentication is untouched.
            $table->enum('type', OtpType::values())
                ->default(OtpType::LOGIN->value)
                ->after('user_id')
                ->index();
        });

        Schema::table('otps', function (Blueprint $table) {
            // Pre-registration OTPs are not tied to a user account yet.
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('otps', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
