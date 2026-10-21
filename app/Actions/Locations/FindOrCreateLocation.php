<?php

namespace App\Actions\Locations;

use App\Models\Location;

class FindOrCreateLocation
{
    /**
     * Attributes are only used when a new location is created.
     *
     * @param  array{address?: string|null, zip_code?: string, city?: string, description?: string|null, notes?: string|null, capacity?: int|null}  $attributes
     */
    public function handle(string $name, array $attributes = []): Location
    {
        $existing = Location::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->first();

        return $existing ?? Location::create([...$attributes, 'name' => $name]);
    }
}
