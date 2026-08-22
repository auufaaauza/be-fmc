<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('study_programs')->cascadeOnDelete();
            $table->foreignId('primary_subject_id')->constrained('subjects');
            $table->decimal('primary_weight', 4, 2);
            $table->foreignId('secondary_subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->decimal('secondary_weight', 4, 2)->nullable()->default(0.00);
            $table->foreignId('interest_category_id')->constrained('interest_categories');
            $table->decimal('interest_weight', 4, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_criteria');
    }
};
