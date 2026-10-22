<?php

namespace App\Actions\Events;

use App\Models\Event;

class DeleteEvent
{
    public function handle(Event $event): void
    {
        $event->delete();
    }
}
