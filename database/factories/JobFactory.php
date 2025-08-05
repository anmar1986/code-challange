<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'title' => $this->faker->jobTitle,
        'description' => $this->faker->paragraph,
        'location' => $this->faker->city,
        'employment_type' => $this->faker->randomElement(['full-time', 'part-time', 'contract']),
        'experience_level' => $this->faker->randomElement(['entry', 'mid', 'senior']),
        'salary_min' => $this->faker->numberBetween(30000, 50000),
        'salary_max' => $this->faker->numberBetween(50000, 100000),
        'salary_currency' => 'EUR',
        'requirements' => $this->faker->sentence,
        'benefits' => $this->faker->sentence,
        'expires_at' => now()->addMonths(rand(1, 6)),
        'is_active' => true,
        'company_id' => \App\Models\Company::factory(),
        'posted_by' => \App\Models\User::factory(),
    ];
}

}
