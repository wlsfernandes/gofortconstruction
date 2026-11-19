<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Placeholder content ported from the Base44 prototype's fallback data.
 *
 * The projects, posts and reviews are samples, not real customers or jobs,
 * so DatabaseSeeder never runs this seeder in production.
 */
class SampleContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceAreaSeeder::class,
            FaqSeeder::class,
            ProjectSeeder::class,
            TestimonialSeeder::class,
            BlogPostSeeder::class,
        ]);
    }
}
