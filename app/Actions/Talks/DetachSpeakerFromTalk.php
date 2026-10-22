<?php

namespace App\Actions\Talks;

use App\Models\Speaker;
use App\Models\Talk;

class DetachSpeakerFromTalk
{
    public function handle(Speaker $speaker, Talk $talk): void
    {
        $talk->speakers()->detach($speaker->id);
    }
}
