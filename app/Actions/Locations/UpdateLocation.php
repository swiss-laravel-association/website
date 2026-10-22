<?php

namespace App\Actions\Locations;

use App\Models\Location;
use Illuminate\Support\Arr;

class UpdateLocation
{
    /**
     * @param  array{name?: string, address?: string|null, zip_code?: string, city?: string, description?: string|null, notes?: string|null, capacity?: int|null}  $attributes
     */
    public function handle(Location $location, array $attributes): Location
    {
        $location->update(Arr::only($attributes, ['name', 'address', 'zip_code', 'city', 'description', 'notes', 'capacity']));

        return $location;
    }
}
