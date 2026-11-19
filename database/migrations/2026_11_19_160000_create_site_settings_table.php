<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Holds one row (id 1) of contact details edited in the admin area.
        // With no row, the site falls back to config/site.php.
        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('phone_display', 50);
            $table->string('phone_short', 30);
            $table->string('email');
            $table->string('service_area', 100);
            $table->string('service_area_tagline', 150);
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
