<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_id')->constrained('recommendations')->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('study_programs')->cascadeOnDelete();
            $table->decimal('primary_score', 5, 2)->nullable();
            $table->decimal('secondary_score', 5, 2)->nullable();
            $table->decimal('interest_score', 5, 2)->nullable();
            $table->decimal('normalized_primary', 8, 6)->nullable();
            $table->decimal('normalized_secondary', 8, 6)->nullable();
            $table->decimal('normalized_interest', 8, 6)->nullable();
            $table->decimal('preference_value', 10, 6);
            $table->integer('rank_position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_results');
    }
};
