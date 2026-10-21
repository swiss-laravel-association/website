<?php

namespace App\Actions\Locations;

use App\Models\Location;

class DeleteLocation
{
    public function handle(Location $location): void
    {
        $location->delete();
    }
}
