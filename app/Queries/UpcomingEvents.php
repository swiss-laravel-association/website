<?php

namespace App\Queries;

use App\Builders\EventBuilder;
use App\Models\Event;

class UpcomingEvents
{
    /**
     * @return EventBuilder<Event>
     */
    public function query(): EventBuilder
    {
        return Event::query()
            ->published()
            ->upcoming()
            ->with(['location'])
            ->orderBy('start_date');
    }
}
