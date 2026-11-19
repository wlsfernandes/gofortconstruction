<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->enum('project_type', ['deck', 'kitchen', 'bathroom', 'outdoor-living', 'renovation', 'other'])->nullable();
            $table->text('message')->nullable();
            $table->enum('preferred_contact', ['phone', 'email'])->default('phone');
            // Indexed: the admin inbox filters by status.
            $table->enum('status', ['new', 'contacted', 'quoted', 'scheduled', 'completed'])->default('new')->index();
            $table->string('source')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
