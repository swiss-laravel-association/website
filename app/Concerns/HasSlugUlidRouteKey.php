<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Route key of "{slug}-{ulid}"; route resolution is keyed on the immutable
 * ULID. The slug portion is cosmetic and never used for lookup.
 *
 * @mixin Model
 */
trait HasSlugUlidRouteKey
{
    public function getRouteKey(): string
    {
        return "{$this->slug}-{$this->ulid}";
    }

    /**
     * Resolve the model by the ULID embedded in the trailing hyphen-delimited
     * token of the route value. Returns null (→ 404) for any non-ULID tail,
     * including old integer IDs.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $ulid = Str::afterLast((string) $value, '-');

        if (! Str::isUlid($ulid)) {
            return null;
        }

        return $this->newQuery()->where('ulid', $ulid)->first();
    }
}
