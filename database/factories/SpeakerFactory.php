<?php

namespace Database\Factories;

use App\Models\Speaker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Speaker>
 */
class SpeakerFactory extends Factory
{
    protected $model = Speaker::class;

    public function definition(): array
    {
        $name = $this->faker->name();
        $handle = $this->faker->userName();

        return [
            'name' => $name,
            'bio' => $this->faker->paragraph(),
            'website' => $this->faker->optional()->url(),
            'github_profile' => $this->faker->optional()->passthrough(sprintf('https://github.com/%s', $handle)),
            'x_profile' => $this->faker->optional()->passthrough(sprintf('https://x.com/%s', $handle)),
            'linkedin_profile' => $this->faker->optional()->passthrough(sprintf('https://www.linkedin.com/in/%s/', $handle)),
            'bluesky_profile' => $this->faker->optional()->passthrough(sprintf('https://bsky.app/profile/%s.bsky.social', $handle)),
            'youtube_profile' => null,
        ];
    }
}
