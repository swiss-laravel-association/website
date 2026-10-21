<?php

namespace App\Actions\Talks;

use App\Models\Talk;
use Illuminate\Support\Arr;

class UpdateTalk
{
    /**
     * Only title, description and recording_url are read from `$attributes`.
     *
     * @param  array{title?: string, description?: string, recording_url?: string|null}  $attributes
     */
    public function handle(Talk $talk, array $attributes): Talk
    {
        $talk->update(Arr::only($attributes, ['title', 'description', 'recording_url']));

        return $talk;
    }
}
