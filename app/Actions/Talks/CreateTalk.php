<?php

namespace App\Actions\Talks;

use App\Models\Talk;

class CreateTalk
{
    /**
     * @param  array{title: string, description: string, recording_url?: string|null}  $attributes
     */
    public function handle(array $attributes): Talk
    {
        return Talk::create($attributes);
    }
}
