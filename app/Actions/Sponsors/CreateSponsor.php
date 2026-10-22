<?php

namespace App\Actions\Sponsors;

use App\Enums\SponsorType;
use App\Models\Sponsor;
use Illuminate\Support\Arr;

class CreateSponsor
{
    /**
     * @param  array{name: string, website: string, type: SponsorType, background_color?: string|null}  $attributes
     */
    public function handle(array $attributes): Sponsor
    {
        return Sponsor::create(Arr::only($attributes, ['name', 'website', 'type', 'background_color']));
    }
}
