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
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            // Conecta el episodio al "show" principal
            $table->foreignId('podcast_id')->constrained('podcasts')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug');
            $table->text('summary')->nullable(); // Resumen corto
            $table->longText('description_md')->nullable();
            $table->longText('transcript_md')->nullable(); // Transcripción

            $table->string('audio_url'); // Dónde está el archivo de audio
            $table->integer('duration_seconds')->unsigned()->nullable();

            // Control editorial
            $table->enum('status', ['draft', 'scheduled', 'published', 'private'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_for')->nullable();

            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['podcast_id', 'slug']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_episodes');
    }
};
