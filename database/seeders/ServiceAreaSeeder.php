<?php

namespace Database\Seeders;

use App\Models\ServiceArea;
use Illuminate\Database\Seeder;

class ServiceAreaSeeder extends Seeder
{
    private const SERVICE_AREAS = [
        ['city' => 'Alpharetta', 'county' => 'Fulton', 'is_primary' => true],
        ['city' => 'Cumming', 'county' => 'Forsyth', 'is_primary' => true],
        ['city' => 'Johns Creek', 'county' => 'Fulton', 'is_primary' => true],
        ['city' => 'Milton', 'county' => 'Fulton', 'is_primary' => true],
        ['city' => 'Roswell', 'county' => 'Fulton', 'is_primary' => true],
        ['city' => 'Suwanee', 'county' => 'Gwinnett', 'is_primary' => false],
        ['city' => 'Dunwoody', 'county' => 'DeKalb', 'is_primary' => false],
        ['city' => 'Sandy Springs', 'county' => 'Fulton', 'is_primary' => false],
        ['city' => 'Canton', 'county' => 'Cherokee', 'is_primary' => false],
        ['city' => 'Woodstock', 'county' => 'Cherokee', 'is_primary' => false],
        ['city' => 'Duluth', 'county' => 'Gwinnett', 'is_primary' => false],
        ['city' => 'Peachtree Corners', 'county' => 'Gwinnett', 'is_primary' => false],
        ['city' => 'Brookhaven', 'county' => 'DeKalb', 'is_primary' => false],
        ['city' => 'Marietta', 'county' => 'Cobb', 'is_primary' => false],
        ['city' => 'Kennesaw', 'county' => 'Cobb', 'is_primary' => false],
        ['city' => 'Holly Springs', 'county' => 'Cherokee', 'is_primary' => false],
    ];

    public function run(): void
    {
        foreach (self::SERVICE_AREAS as $serviceArea) {
            ServiceArea::updateOrCreate(
                ['city' => $serviceArea['city'], 'state' => 'Georgia'],
                $serviceArea,
            );
        }
    }
}
