<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Models\Event;

class UnpublishEvent
{
    public function handle(Event $event): Event
    {
        $event->update(['is_published' => false]);

        return $event;
    }
}
