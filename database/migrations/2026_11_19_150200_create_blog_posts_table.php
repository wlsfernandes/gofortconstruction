<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            // Unique: public post URLs are /blog/{slug}.
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->text('content');
            $table->string('featured_image')->nullable();
            $table->enum('category', ['tips', 'trends', 'projects', 'news', 'guides'])->nullable();
            $table->jsonb('tags')->default('[]');
            $table->string('author')->default('Fort Construction Team');
            $table->boolean('is_published')->default(false);
            $table->date('publish_date')->nullable();
            $table->timestampsTz();

            // The blog index lists published posts, newest first.
            $table->index(['is_published', 'publish_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
