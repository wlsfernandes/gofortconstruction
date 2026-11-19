<?php

namespace Database\Factories;

use App\Enums\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'excerpt' => fake()->sentence(),
            'content' => fake()->paragraphs(4, true),
            'category' => fake()->randomElement(BlogCategory::cases()),
            'tags' => [],
            'is_published' => true,
            'publish_date' => fake()->date(),
        ];
    }
}
