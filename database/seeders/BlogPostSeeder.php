<?php

namespace Database\Seeders;

use App\Enums\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            ['deck-design-trends-2024', '10 Deck Design Trends for 2024', BlogCategory::Trends, '2024-02-15', 'photo-1591825729269-caeb344f6df2', 'Discover the latest deck design trends that homeowners are loving this year, from composite materials to multi-level layouts.'],
            ['kitchen-remodel-checklist', 'Kitchen Remodel Checklist: What to Consider', BlogCategory::Guides, '2024-02-01', 'photo-1556909114-f6e7ad7d3136', 'Planning a kitchen renovation? Use our comprehensive checklist to ensure you\'ve thought of everything before starting.'],
            ['outdoor-materials-guide', 'Choosing the Right Materials for Your Outdoor Space', BlogCategory::Tips, '2024-01-20', 'photo-1600566753190-17f0baa2a6c3', 'A guide to selecting durable, beautiful materials for your outdoor living area that will withstand Georgia\'s climate.'],
            ['small-bathroom-ideas', 'Maximizing Small Bathroom Spaces', BlogCategory::Tips, '2024-01-10', 'photo-1552321554-5fefe8c9ef14', 'Creative solutions for making the most of limited bathroom square footage without sacrificing style or functionality.'],
        ];

        foreach ($posts as [$slug, $title, $category, $publishDate, $unsplashPhotoId, $excerpt]) {
            BlogPost::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'category' => $category,
                    'publish_date' => $publishDate,
                    'featured_image' => "/images/unsplash/{$unsplashPhotoId}.jpg",
                    'excerpt' => $excerpt,
                    // The prototype had no article bodies; the excerpt stands in until real posts are written.
                    'content' => $excerpt,
                    'is_published' => true,
                ],
            );
        }
    }
}
