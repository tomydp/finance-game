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
        // Esta tabla TAMPOCO necesita title, slug, id, etc.
        Schema::create('lesson_podcast_episode', function (Blueprint $table) {
            // Conecta con tu tabla 'lessons'
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();

            // Conecta con nuestra nueva tabla 'podcast_episodes'
            $table->foreignId('podcast_episode_id')->constrained('podcast_episodes')->cascadeOnDelete();

            $table->timestamps();

            // Clave primaria compuesta
            $table->primary(['lesson_id', 'podcast_episode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_podcast_episode');
    }
};
