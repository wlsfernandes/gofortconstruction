<?php

namespace Database\Factories;

use App\Enums\ContactProjectType;
use App\Enums\PreferredContact;
use App\Models\ContactSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactSubmission>
 */
class ContactSubmissionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'project_type' => fake()->randomElement(ContactProjectType::cases()),
            'message' => fake()->paragraph(),
            'preferred_contact' => PreferredContact::Phone,
        ];
    }
}
