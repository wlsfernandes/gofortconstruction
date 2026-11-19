<?php

namespace Database\Seeders;

use App\Enums\ProjectCategory;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /** The prototype homepage showed the first four projects as featured. */
    private const FEATURED_PROJECT_COUNT = 4;

    public function run(): void
    {
        $projects = [
            ['Modern Composite Deck', 'Cumming', ProjectCategory::Deck, 'A stunning multi-level deck with built-in seating and lighting.', 'photo-1591825729269-caeb344f6df2'],
            ['Contemporary Kitchen Remodel', 'Alpharetta', ProjectCategory::Kitchen, 'Complete kitchen transformation with quartz countertops and custom cabinetry.', 'photo-1556909114-f6e7ad7d3136'],
            ['Spa-Inspired Bathroom', 'Johns Creek', ProjectCategory::Bathroom, 'Luxurious master bathroom with heated floors and walk-in shower.', 'photo-1552321554-5fefe8c9ef14'],
            ['Outdoor Kitchen Paradise', 'Milton', ProjectCategory::OutdoorLiving, 'Full outdoor kitchen with granite counters and built-in grill.', 'photo-1600566753190-17f0baa2a6c3'],
            ['Whole-Home Renovation', 'Roswell', ProjectCategory::Renovation, 'Complete interior renovation modernizing a 1990s home.', 'photo-1600607687939-ce8a6c25118c'],
            ['Custom Built-In Library', 'Dunwoody', ProjectCategory::Custom, 'Floor-to-ceiling custom bookshelves with rolling ladder.', 'photo-1600585154526-990dced4db0d'],
            ['Covered Patio Addition', 'Suwanee', ProjectCategory::OutdoorLiving, 'Beautiful covered patio with outdoor fireplace.', 'photo-1600566752355-35792bedcfea'],
            ['Farmhouse Kitchen Update', 'Canton', ProjectCategory::Kitchen, 'Modern farmhouse kitchen with shaker cabinets.', 'photo-1556909212-d5b604d0c90d'],
        ];

        foreach ($projects as $position => [$title, $location, $category, $shortDescription, $unsplashPhotoId]) {
            Project::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'location' => $location,
                    'category' => $category,
                    'short_description' => $shortDescription,
                    'featured_image' => "/images/unsplash/{$unsplashPhotoId}.jpg",
                    'is_featured' => $position < self::FEATURED_PROJECT_COUNT,
                ],
            );
        }
    }
}
