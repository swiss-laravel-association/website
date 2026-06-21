<?php

namespace Database\Factories;

use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Talk>
 */
class TalkFactory extends Factory
{
    protected $model = Talk::class;

    public function definition(): array
    {
        $topics = [
            'Real-Time Laravel with WebSockets',
            'Queues, Horizon and You',
            'Testing Livewire Components with Pest',
            'A Pragmatic Guide to Filament',
            'Refactoring Eloquent Queries',
            'From Vue to Livewire — and Back',
            'Building a Multi-Tenant SaaS in Laravel',
            'PHP 8.5 in Production',
            'Mastering Laravel Octane',
            'Designing APIs that Developers Love',
        ];

        return [
            'title' => $this->faker->randomElement($topics),
            'description' => $this->faker->paragraph(),
            'recording_url' => $this->faker->optional(0.4)->url(),
        ];
    }
}
