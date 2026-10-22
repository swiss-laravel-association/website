<?php

namespace App\Actions\Locations;

use App\Models\Location;
use Illuminate\Support\Arr;

class CreateLocation
{
    /**
     * @param  array{name: string, address?: string|null, zip_code: string, city: string, description?: string|null, notes?: string|null, capacity?: int|null}  $attributes
     */
    public function handle(array $attributes): Location
    {
        return Location::create(Arr::only($attributes, ['name', 'address', 'zip_code', 'city', 'description', 'notes', 'capacity']));
    }
}
