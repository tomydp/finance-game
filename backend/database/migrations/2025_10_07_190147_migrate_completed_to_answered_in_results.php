<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // asegura answered_at
            if (!Schema::hasColumn('results', 'answered_at')) {
                // si no tenés 'score', podés poner after('exercise_id')
                $after = Schema::hasColumn('results', 'score') ? 'score' : 'exercise_id';
                $table->timestamp('answered_at')->nullable()->after($after);
            }
            // asegura is_correct
            if (!Schema::hasColumn('results', 'is_correct')) {
                $table->boolean('is_correct')->default(false)->after('answered_at');
            }
        });

        // backfill answered_at con completed_at (si existía y answered_at está vacío)
        if (Schema::hasColumn('results', 'completed_at')) {
            DB::statement('UPDATE results SET answered_at = completed_at WHERE answered_at IS NULL AND completed_at IS NOT NULL');

            // elimina completed_at (ya no lo usamos)
            Schema::table('results', function (Blueprint $table) {
                $table->dropColumn('completed_at');
            });
        }
    }

    public function down(): void
    {
        // recrea completed_at y opcionalmente limpia answered_at
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'completed_at')) {
                $after = Schema::hasColumn('results', 'score') ? 'score' : 'exercise_id';
                $table->timestamp('completed_at')->nullable()->after($after);
            }
        });

        DB::statement('UPDATE results SET completed_at = answered_at WHERE completed_at IS NULL AND answered_at IS NOT NULL');

        // si querés un down simétrico:
        // Schema::table('results', function (Blueprint $table) {
        //     if (Schema::hasColumn('results', 'answered_at')) {
        //         $table->dropColumn('answered_at');
        //     }
        // });
    }
};
