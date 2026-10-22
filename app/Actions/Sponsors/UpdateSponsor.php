<?php

namespace App\Actions\Sponsors;

use App\Enums\SponsorType;
use App\Models\Sponsor;
use Illuminate\Support\Arr;

class UpdateSponsor
{
    /**
     * @param  array{name?: string, website?: string, type?: SponsorType, background_color?: string|null}  $attributes
     */
    public function handle(Sponsor $sponsor, array $attributes): Sponsor
    {
        $sponsor->update(Arr::only($attributes, ['name', 'website', 'type', 'background_color']));

        return $sponsor;
    }
}
