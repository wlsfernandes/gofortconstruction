<?php

namespace Database\Factories;

use App\Enums\TestimonialSource;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => fake()->name(),
            'location' => fake()->city(),
            'content' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'project_type' => fake()->words(2, true),
            'is_featured' => false,
            'source' => fake()->randomElement(TestimonialSource::cases()),
        ];
    }
}
