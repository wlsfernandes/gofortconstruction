<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('client_name');
            $table->string('location')->nullable();
            $table->text('content');
            $table->unsignedSmallInteger('rating')->default(5);
            $table->string('project_type')->nullable();
            $table->string('image')->nullable();
            // Indexed: the homepage filters on featured testimonials.
            $table->boolean('is_featured')->default(false)->index();
            $table->enum('source', ['google', 'website', 'houzz', 'other'])->default('website');
            $table->timestampsTz();
        });

        // SQLite cannot add a constraint to an existing table, so only PostgreSQL gets it.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE testimonials ADD CONSTRAINT testimonials_rating_between_1_and_5 CHECK (rating BETWEEN 1 AND 5)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
