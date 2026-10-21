<?php

namespace App\Actions\Speakers;

use App\Models\Speaker;

class UpdateSpeaker
{
    /**
     * @param  array{name?: string, bio?: string|null, website?: string|null, github_profile?: string|null, x_profile?: string|null, linkedin_profile?: string|null, bluesky_profile?: string|null, youtube_profile?: string|null}  $attributes
     */
    public function handle(Speaker $speaker, array $attributes): Speaker
    {
        $speaker->update($attributes);

        return $speaker;
    }
}
