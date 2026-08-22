<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->text('counselor_notes')->nullable()->after('calculated_at');
            $table->foreignId('counselor_id')->nullable()->constrained('users')->nullOnDelete()->after('counselor_notes');
            $table->timestamp('counselor_reviewed_at')->nullable()->after('counselor_id');
        });

        Schema::table('study_programs', function (Blueprint $table) {
            $table->json('universities')->nullable()->after('learning_path');
        });
    }

    public function down(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropForeign(['counselor_id']);
            $table->dropColumn(['counselor_notes', 'counselor_id', 'counselor_reviewed_at']);
        });

        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropColumn('universities');
        });
    }
};
