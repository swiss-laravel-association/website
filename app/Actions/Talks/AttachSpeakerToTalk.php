<?php

namespace App\Actions\Talks;

use App\Models\Speaker;
use App\Models\Talk;

class AttachSpeakerToTalk
{
    public function handle(Speaker $speaker, Talk $talk): void
    {
        $talk->speakers()->syncWithoutDetaching([$speaker->id]);
    }
}
