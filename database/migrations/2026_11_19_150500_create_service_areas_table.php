<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_areas', function (Blueprint $table): void {
            $table->id();
            $table->string('city');
            $table->string('county')->nullable();
            $table->string('state')->default('Georgia');
            $table->text('description')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestampsTz();

            // One row per city; the unique index also serves the list ordered by city.
            $table->unique(['city', 'state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_areas');
    }
};
