<?php

namespace App\Actions\Events;

use App\Models\Event;

class CreateEvent
{
    /**
     * New events always start unpublished; any `is_published` input is ignored.
     *
     * @param  array{name: string, location_id?: int|null, start_date: string, end_date: string, description?: string|null, meetup_link?: string|null}  $attributes
     */
    public function handle(array $attributes): Event
    {
        return Event::create([...$attributes, 'is_published' => false]);
    }
}
