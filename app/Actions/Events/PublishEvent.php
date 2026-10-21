<?php

namespace App\Actions\Events;

use App\Models\Event;

class PublishEvent
{
    public function handle(Event $event): Event
    {
        $event->update(['is_published' => true]);

        return $event;
    }
}
