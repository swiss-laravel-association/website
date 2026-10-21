<?php

namespace App\Actions\Speakers;

use App\Models\Speaker;

class CreateSpeaker
{
    /**
     * @param  array{name: string, bio?: string|null, website?: string|null, github_profile?: string|null, x_profile?: string|null, linkedin_profile?: string|null, bluesky_profile?: string|null, youtube_profile?: string|null}  $attributes
     */
    public function handle(array $attributes): Speaker
    {
        return Speaker::create($attributes);
    }
}
