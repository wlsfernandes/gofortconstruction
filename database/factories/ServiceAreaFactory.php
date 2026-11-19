<?php

namespace Database\Factories;

use App\Models\ServiceArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceArea>
 */
class ServiceAreaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city' => fake()->unique()->city(),
            'county' => fake()->lastName(),
            'state' => 'Georgia',
            'is_primary' => false,
        ];
    }
}
