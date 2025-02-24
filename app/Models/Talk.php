<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $recording_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Speaker> $speakers
 * @property-read int|null $speakers_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk whereRecordingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talk whereUpdatedAt($value)
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
