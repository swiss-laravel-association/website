<?php

namespace App\Actions\Locations;

use App\Models\Location;

class CreateLocation
{
    /**
     * @param  array{name: string, address?: string|null, zip_code: string, city: string, description?: string|null, notes?: string|null, capacity?: int|null}  $attributes
     */
    public function handle(array $attributes): Location
    {
        return Location::create($attributes);
    }
}
