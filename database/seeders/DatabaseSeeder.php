<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        if (app()->isProduction()) {
            $this->command?->warn('Skipping SampleContentSeeder: sample projects and reviews must not reach production.');

            return;
        }

        $this->call(SampleContentSeeder::class);
    }
}
