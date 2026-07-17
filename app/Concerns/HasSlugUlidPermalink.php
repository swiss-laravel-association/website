<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Builds a "{slug}-{ulid}" permalink where the ULID is the identity and the
 * slug is cosmetic. The framework route key is left untouched so Filament and
 * other consumers keep resolving records by the primary key; front-end URLs are
 * built from {@see permalink()}.
 *
 * @mixin Model
 */
trait HasSlugUlidPermalink
{
    public function permalink(): string
    {
        return "{$this->slug}-{$this->ulid}";
    }

    /**
     * Resolve the model by the ULID embedded in the trailing hyphen-delimited
     * token of the route value. The ULID is lowercased so an uppercase form of
     * a valid ULID still resolves (and then redirects to the canonical URL).
     * Returns null (→ 404) for any non-ULID tail, including old integer IDs.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $ulid = strtolower(Str::afterLast((string) $value, '-'));

        if (! Str::isUlid($ulid)) {
            return null;
        }

        return $this->newQuery()->where('ulid', $ulid)->first();
    }
}
