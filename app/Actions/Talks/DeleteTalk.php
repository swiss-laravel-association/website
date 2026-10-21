<?php

namespace App\Actions\Talks;

use App\Models\Talk;

class DeleteTalk
{
    public function handle(Talk $talk): void
    {
        $talk->speakers()->detach();
        $talk->events()->detach();
        $talk->delete();
    }
}
