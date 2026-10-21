<?php

namespace App\Actions\Events;

use App\Models\Event;
use Illuminate\Support\Arr;

class UpdateEvent
{
    /**
     * Only name, location_id, start_date, end_date, description and meetup_link are read from
     * `$attributes`; publishing goes through PublishEvent and UnpublishEvent.
     *
     * @param  array{name?: string, location_id?: int|null, start_date?: string, end_date?: string, description?: string|null, meetup_link?: string|null}  $attributes
     */
    public function handle(Event $event, array $attributes): Event
    {
        $event->update(Arr::only($attributes, ['name', 'location_id', 'start_date', 'end_date', 'description', 'meetup_link']));

        return $event;
    }
}
