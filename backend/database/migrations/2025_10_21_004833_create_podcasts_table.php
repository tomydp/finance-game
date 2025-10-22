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
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description_md')->nullable();
            $table->string('cover_image_url')->nullable(); // Portada del show
            $table->enum('status', ['draft', 'published', 'private'])->default('draft');

            // Auditoría (quién lo creó)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps(); // created_at y updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
