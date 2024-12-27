<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        ];
    }

    public function current(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'sign_in_start' => Carbon::now()->startOfDay(),
            'sign_in_end' => Carbon::now()->endOfDay()
        ]);
    }

    public function past(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'sign_in_start' => Carbon::now()->subMonth()->startOfDay(),
            'sign_in_end' => Carbon::now()->subMonth()->endOfDay()
        ]);
    }

    public function future(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'sign_in_start' => Carbon::now()->addMonth()->startOfDay(),
            'sign_in_end' => Carbon::now()->addMonth()->endOfDay()
        ]);
    }
}
