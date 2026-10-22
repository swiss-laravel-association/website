<?php

namespace App\Actions\Talks;

use App\Models\Talk;
use Illuminate\Support\Arr;

class CreateTalk
{
    /**
     * Only title, description and recording_url are read from `$attributes`.
     *
     * @param  array{title: string, description: string, recording_url?: string|null}  $attributes
     */
    public function handle(array $attributes): Talk
    {
        return Talk::create(Arr::only($attributes, ['title', 'description', 'recording_url']));
    }
}
