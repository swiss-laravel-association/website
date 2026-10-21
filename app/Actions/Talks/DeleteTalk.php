<?php

namespace App\Actions\Talks;

use App\Models\Talk;
use Illuminate\Support\Facades\DB;

class DeleteTalk
{
    /**
     * `event_talk.talk_id` has no cascade, so the event links must be removed before the talk.
     * `talk_speaker.talk_id` cascades on delete; detaching the speakers here is redundant but harmless.
     */
    public function handle(Talk $talk): void
    {
        DB::transaction(function () use ($talk): void {
            $talk->speakers()->detach();
            $talk->events()->detach();
            $talk->delete();
        });
    }
}
