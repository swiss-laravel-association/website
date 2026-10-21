<?php

namespace App\Actions\Talks;

use App\Models\Talk;

class UpdateTalk
{
    /**
     * @param  array{title?: string, description?: string, recording_url?: string|null}  $attributes
     */
    public function handle(Talk $talk, array $attributes): Talk
    {
        $talk->update($attributes);

        return $talk;
    }
}
