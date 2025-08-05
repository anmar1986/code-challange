<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchLog>
 */
class SearchLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'search_term' => $this->faker->word,
        'location' => $this->faker->city,
        'employment_type' => $this->faker->randomElement(['full-time', 'part-time']),
        'experience_level' => $this->faker->randomElement(['entry', 'mid', 'senior']),
        'salary_min' => $this->faker->numberBetween(30000, 50000),
        'salary_max' => $this->faker->numberBetween(50000, 100000),
        'filters' => ['remote' => true],
        'results_count' => rand(0, 100),
        'ip_address' => $this->faker->ipv4,
        'user_agent' => $this->faker->userAgent,
        'session_id' => $this->faker->uuid,
        'user_id' => \App\Models\User::factory(),
    ];
}

}
