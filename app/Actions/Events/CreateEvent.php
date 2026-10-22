<?php

namespace App\Actions\Events;

use App\Models\Event;
use Illuminate\Support\Arr;

class CreateEvent
{
    /**
     * New events always start unpublished. Only name, location_id, start_date, end_date,
     * description and meetup_link are read from `$attributes`.
     *
     * @param  array{name: string, location_id?: int|null, start_date: string, end_date: string, description?: string|null, meetup_link?: string|null}  $attributes
     */
    public function handle(array $attributes): Event
    {
        return Event::create([
            ...Arr::only($attributes, ['name', 'location_id', 'start_date', 'end_date', 'description', 'meetup_link']),
            'is_published' => false,
        ]);
    }
}
