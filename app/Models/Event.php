<?php

namespace App\Models;

use App\Builders\EventBuilder;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Override;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

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
 * @property-read Location|null $location
 * @property-read Collection<int, Talk> $talks
 * @property-read int|null $talks_count
 *
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static EventBuilder<static>|Event newModelQuery()
 * @method static EventBuilder<static>|Event newQuery()
 * @method static EventBuilder<static>|Event notPublished()
 * @method static EventBuilder<static>|Event past()
 * @method static EventBuilder<static>|Event published()
 * @method static EventBuilder<static>|Event query()
 * @method static EventBuilder<static>|Event upcoming()
 * @method static EventBuilder<static>|Event whereCreatedAt($value)
 * @method static EventBuilder<static>|Event whereDescription($value)
 * @method static EventBuilder<static>|Event whereEndDate($value)
 * @method static EventBuilder<static>|Event whereId($value)
 * @method static EventBuilder<static>|Event whereIsPublished($value)
 * @method static EventBuilder<static>|Event whereLocationId($value)
 * @method static EventBuilder<static>|Event whereMeetupLink($value)
 * @method static EventBuilder<static>|Event whereName($value)
 * @method static EventBuilder<static>|Event whereStartDate($value)
 * @method static EventBuilder<static>|Event whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    use HasSEO;

    /**
     * @return EventBuilder<Event>
     */
    #[Override]
    public function newEloquentBuilder($query): EventBuilder
    {
        return new EventBuilder($query);
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->name,
            description: Str::limit($this->description, 160),
            published_time: $this->start_date,
            type: 'event',
        );
    }

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

    /**
     * @return Attribute<CarbonPeriodImmutable, never>
     */
    protected function period(): Attribute
    {
        return Attribute::get(fn (): CarbonPeriodImmutable => CarbonPeriodImmutable::create(
            $this->start_date,
            $this->end_date,
        ));
    }

    public function displayPeriod(): string
    {
        if ($this->start_date->isSameDay($this->end_date)) {
            return sprintf(
                '%s %s - %s',
                $this->start_date->format('d.m.y'),
                $this->start_date->format('H:i'),
                $this->end_date->format('H:i'),
            );
        }

        return sprintf(
            '%s - %s',
            $this->start_date->format('d.m.y H:i'),
            $this->end_date->format('d.m.y H:i'),
        );
    }

    public function showUrl(): Attribute
    {
        return Attribute::get(fn (): string => route('events.show', $this));
    }
}
