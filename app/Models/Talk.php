<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $recording_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read Collection<int, \App\Models\Speaker> $speakers
 * @property-read int|null $speakers_count
 *
 * @method static Builder<static>|Talk newModelQuery()
 * @method static Builder<static>|Talk newQuery()
 * @method static Builder<static>|Talk query()
 * @method static Builder<static>|Talk whereCreatedAt($value)
 * @method static Builder<static>|Talk whereDescription($value)
 * @method static Builder<static>|Talk whereId($value)
 * @method static Builder<static>|Talk whereRecordingUrl($value)
 * @method static Builder<static>|Talk whereTitle($value)
 * @method static Builder<static>|Talk whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Talk extends Model
{
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
