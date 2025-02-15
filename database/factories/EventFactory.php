<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'name' => $this->faker->text(100),
            'description' => $this->faker->paragraph(2),
            'location' => $this->faker->text(10)
        ];
    }

    public function current(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => Carbon::now()->startOfDay(),
            'end_date' => Carbon::now()->endOfDay(),
            'sign_in_start' => Carbon::now()->startOfDay(),
            'sign_in_end' => Carbon::now()->endOfDay()
        ]);
    }

    public function past(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => Carbon::now()->subMonth()->startOfDay(),
            'end_date' => Carbon::now()->subMonth()->endOfDay(),
            'sign_in_start' => Carbon::now()->subMonth()->startOfDay(),
            'sign_in_end' => Carbon::now()->subMonth()->endOfDay()
        ]);
    }

    public function future(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'start_date' => Carbon::now()->addMonth()->startOfDay(),
            'end_date' => Carbon::now()->addMonth()->endOfDay(),
            'sign_in_start' => Carbon::now()->addMonth()->startOfDay(),
            'sign_in_end' => Carbon::now()->addMonth()->endOfDay()
        ]);
    }
}
