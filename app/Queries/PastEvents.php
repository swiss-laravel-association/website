<?php

namespace App\Queries;

use App\Builders\EventBuilder;
use App\Models\Event;

class PastEvents
{
    /**
     * @return EventBuilder<Event>
     */
    public function query(): EventBuilder
    {
        return Event::query()
            ->published()
            ->past()
            ->with(['location'])
            ->orderBy('start_date', 'desc');
    }
}
