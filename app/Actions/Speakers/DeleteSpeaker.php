<?php

namespace App\Actions\Speakers;

use App\Models\Speaker;
use Illuminate\Support\Facades\DB;

class DeleteSpeaker
{
    public function handle(Speaker $speaker): void
    {
        DB::transaction(function () use ($speaker): void {
            $speaker->talks()->detach();
            $speaker->delete();
        });
    }
}
