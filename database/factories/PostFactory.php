<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(nb: $this->faker->numberBetween(20, 200), asText: true),
            'excerpt' => $this->faker->paragraph(2),
            'published_at' => now()->subDays($this->faker->numberBetween(0, 365))->startOfDay(),
        ];
    }
}
