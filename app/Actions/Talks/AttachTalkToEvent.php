<?php

declare(strict_types=1);

namespace App\Actions\Talks;

use App\Models\Event;
use App\Models\Talk;

class AttachTalkToEvent
{
    public function handle(Talk $talk, Event $event): void
    {
        $event->talks()->syncWithoutDetaching([$talk->id]);
    }
}
