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
        Schema::create('pre_registrations', function (Blueprint $table) {
            $table->id();

            $table->string('student_full_name');
            $table->string('father_name');
            $table->string('mother_full_name');

            // Stored as a string to preserve leading zeros.
            $table->string('national_code', 10)->unique();

            $table->unsignedTinyInteger('grade');

            $table->decimal('previous_year_average', 4, 2);
            $table->decimal('previous_year_discipline_score', 4, 2);

            $table->string('phone_number');
            // Filled only after the mobile number passes OTP verification.
            $table->timestamp('phone_verified_at')->nullable();

            $table->timestamps();

            $table->index('grade');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_registrations');
    }
};
