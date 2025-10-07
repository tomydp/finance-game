<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'status')) {
                $table->string('status', 20)->default('activo')->index()->after('difficulty');
            }
        });

        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'status')) {
                $table->string('status', 20)->default('activo')->index()->after('order');
            }
        });

        Schema::table('exercises', function (Blueprint $table) {
            if (!Schema::hasColumn('exercises', 'status')) {
                $table->string('status', 20)->default('activo')->index()->after('correct_answer');
            }
        });

        DB::table('courses')->whereNull('status')->update(['status' => 'activo']);
        DB::table('lessons')->whereNull('status')->update(['status' => 'activo']);
        DB::table('exercises')->whereNull('status')->update(['status' => 'activo']);
    }

    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            if (Schema::hasColumn('exercises', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });

        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });
    }
};
