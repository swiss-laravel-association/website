<?php

namespace App\Actions\Speakers;

use App\Models\Speaker;

class DeleteSpeaker
{
    public function handle(Speaker $speaker): void
    {
        $speaker->talks()->detach();
        $speaker->delete();
    }
}
