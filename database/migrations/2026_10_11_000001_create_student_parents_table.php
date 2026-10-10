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
        Schema::create('student_parents', function (Blueprint $table) {
            $table->id();

            // Students and parents are both stored in the `users` table.
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('relationship_type', ['father', 'mother']);

            $table->timestamps();

            // A student may have at most one father and one mother relationship.
            $table->unique(['student_id', 'relationship_type']);

            // Prevent the exact same relationship from being recorded twice.
            $table->unique(['student_id', 'parent_id', 'relationship_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_parents');
    }
};
