<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            // Unique: public detail URLs are /projects/{slug}.
            $table->string('slug')->unique();
            $table->string('location')->nullable();
            $table->enum('category', ['deck', 'kitchen', 'bathroom', 'outdoor-living', 'renovation', 'custom']);
            $table->string('short_description', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->jsonb('before_images')->default('[]');
            $table->jsonb('after_images')->default('[]');
            $table->jsonb('gallery_images')->default('[]');
            $table->jsonb('tags')->default('[]');
            // Indexed: the homepage filters on featured projects.
            $table->boolean('is_featured')->default(false)->index();
            $table->date('completion_date')->nullable();
            $table->text('testimonial')->nullable();
            $table->string('client_name')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
