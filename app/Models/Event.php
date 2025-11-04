<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property CarbonImmutable $start_date
 * @property CarbonImmutable $end_date
 * @property string|null $meetup_link
 * @property int $is_published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $location_id
 * @property-read \App\Models\Location|null $location
 * @property-read Collection<int, \App\Models\Talk> $talks
 * @property-read int|null $talks_count
 *
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event whereCreatedAt($value)
 * @method static Builder<static>|Event whereDescription($value)
 * @method static Builder<static>|Event whereEndDate($value)
 * @method static Builder<static>|Event whereId($value)
 * @method static Builder<static>|Event whereIsPublished($value)
 * @method static Builder<static>|Event whereLocationId($value)
 * @method static Builder<static>|Event whereMeetupLink($value)
 * @method static Builder<static>|Event whereName($value)
 * @method static Builder<static>|Event whereStartDate($value)
 * @method static Builder<static>|Event whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'start_date' => 'immutable_datetime',
            'end_date' => 'immutable_datetime',
        ];
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return BelongsToMany<Talk, $this>
     */
    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }
}
