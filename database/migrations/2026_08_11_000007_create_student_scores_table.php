<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->decimal('sem1', 5, 2)->nullable();
            $table->decimal('sem2', 5, 2)->nullable();
            $table->decimal('sem3', 5, 2)->nullable();
            $table->decimal('sem4', 5, 2)->nullable();
            $table->decimal('sem5', 5, 2)->nullable();
            $table->decimal('score', 5, 2);
            $table->timestamps();
            $table->unique(['user_id', 'subject_id'], 'uq_student_subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};
