<?php

namespace App\Actions\Events;

use App\Models\Event;

class UpdateEvent
{
    /**
     * @param  array{name?: string, location_id?: int|null, start_date?: string, end_date?: string, description?: string|null, meetup_link?: string|null}  $attributes
     */
    public function handle(Event $event, array $attributes): Event
    {
        $event->update($attributes);

        return $event;
    }
}
