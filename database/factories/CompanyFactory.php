<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'name' => $this->faker->company,
        'description' => $this->faker->paragraph,
        'location' => $this->faker->city,
        'website' => $this->faker->url,
        'industry' => $this->faker->word,
        'size' => $this->faker->randomElement(['1-10', '11-50', '51-200', '201-500', '500-1000']),
        'founded_year' => $this->faker->year,
        'logo_url' => $this->faker->imageUrl(150, 150, 'business', true),
        'social_links' => ['linkedin' => $this->faker->url],
    ];
}

}
