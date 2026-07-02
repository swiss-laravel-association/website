<?php

namespace App\Models;

use App\Concerns\HasSlugUlidRouteKey;
use Database\Factories\TalkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $recording_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $ulid
 * @property string|null $slug
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @property-read Collection<int, Speaker> $speakers
 * @property-read int|null $speakers_count
 *
 * @method static \Database\Factories\TalkFactory factory($count = null, $state = [])
 * @method static Builder<static>|Talk newModelQuery()
 * @method static Builder<static>|Talk newQuery()
 * @method static Builder<static>|Talk query()
 * @method static Builder<static>|Talk whereCreatedAt($value)
 * @method static Builder<static>|Talk whereDescription($value)
 * @method static Builder<static>|Talk whereId($value)
 * @method static Builder<static>|Talk whereRecordingUrl($value)
 * @method static Builder<static>|Talk whereSlug($value)
 * @method static Builder<static>|Talk whereTitle($value)
 * @method static Builder<static>|Talk whereUlid($value)
 * @method static Builder<static>|Talk whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Talk extends Model
{
    /** @use HasFactory<TalkFactory> */
    use HasFactory;

    use HasSlug, HasSlugUlidRouteKey {
        HasSlugUlidRouteKey::getRouteKey insteadof HasSlug;
        HasSlugUlidRouteKey::resolveRouteBinding insteadof HasSlug;
    }
    use HasUlids;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    /**
     * @return BelongsToMany<Speaker, $this>
     */
    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class, 'talk_speaker');
    }

    /**
     * @return BelongsToMany<Event, $this>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_talk');
    }
}
