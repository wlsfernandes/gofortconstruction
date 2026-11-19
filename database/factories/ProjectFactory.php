<?php

namespace Database\Factories;

use App\Enums\ProjectCategory;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'location' => fake()->city(),
            'category' => fake()->randomElement(ProjectCategory::cases()),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'featured_image' => null,
            'before_images' => [],
            'after_images' => [],
            'gallery_images' => [],
            'tags' => [],
            'is_featured' => false,
            'completion_date' => fake()->date(),
        ];
    }
}
