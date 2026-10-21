<?php

namespace App\Actions\Locations;

use App\Models\Event;
use App\Models\Location;

class DeleteLocation
{
    /**
     * `events.location_id` has no foreign key, so events are detached by hand.
     */
    public function handle(Location $location): void
    {
        Event::query()->where('location_id', $location->id)->update(['location_id' => null]);
        $location->delete();
    }
}
