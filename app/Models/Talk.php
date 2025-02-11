<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Tags\HasTags;

class Talk extends Model implements Sortable
{
    use HasTags;
    use SortableTrait;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Not used for the moment. We should prefer Spatie's tags package.

    /*     public function tags(): BelongsToMany
        {
            return $this->belongsToMany(Tag::class);
        } */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'images' => 'array',
        ];

    }
}
