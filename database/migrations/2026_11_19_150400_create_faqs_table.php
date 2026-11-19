<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->text('question');
            $table->text('answer');
            $table->enum('category', ['general', 'pricing', 'process', 'services', 'warranty'])->default('general');
            // Indexed: the FAQ page lists questions by display order.
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
