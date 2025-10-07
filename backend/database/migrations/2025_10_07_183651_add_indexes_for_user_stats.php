<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->index(['user_id', 'answered_at']);
            $table->index(['user_id', 'exercise_id', 'is_correct']);
        });
    }
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex(['results_user_id_answered_at_index']);
            $table->dropIndex(['results_user_id_exercise_id_is_correct_index']);
        });
    }
};
