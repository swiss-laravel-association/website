<?php

namespace Database\Factories;

use App\Enums\SponsorType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsor>
 */
class SponsorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => SponsorType::Founding,
            'name' => $this->faker->company(),
            'website' => 'https://example.com',
            'background_color' => '#ff6600',
            'order' => 0,
        ];
    }

    public function isFoundingSponsor(): Factory|SponsorFactory
    {
        return $this->state(fn (array $attributes): array => [
            'type' => SponsorType::Founding,
        ]);
    }

    public function isLocationSponsor(): Factory|SponsorFactory
    {
        return $this->state(fn (array $attributes): array => [
            'type' => SponsorType::Founding,
        ]);
    }
}
