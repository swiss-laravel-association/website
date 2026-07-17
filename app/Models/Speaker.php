<?php

namespace App\Models;

use App\Concerns\HasSlugUlidRouteKey;
use Database\Factories\SpeakerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property string $name
 * @property string|null $bio
 * @property string|null $website
 * @property string|null $github_profile
 * @property string|null $x_profile
 * @property string|null $linkedin_profile
 * @property string|null $bluesky_profile
 * @property string|null $youtube_profile
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $ulid
 * @property string|null $slug
 * @property-read Collection<int, Talk> $talks
 * @property-read int|null $talks_count
 *
 * @method static \Database\Factories\SpeakerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Speaker newModelQuery()
 * @method static Builder<static>|Speaker newQuery()
 * @method static Builder<static>|Speaker query()
 * @method static Builder<static>|Speaker whereBio($value)
 * @method static Builder<static>|Speaker whereBlueskyProfile($value)
 * @method static Builder<static>|Speaker whereCreatedAt($value)
 * @method static Builder<static>|Speaker whereGithubProfile($value)
 * @method static Builder<static>|Speaker whereId($value)
 * @method static Builder<static>|Speaker whereLinkedinProfile($value)
 * @method static Builder<static>|Speaker whereName($value)
 * @method static Builder<static>|Speaker whereSlug($value)
 * @method static Builder<static>|Speaker whereUlid($value)
 * @method static Builder<static>|Speaker whereUpdatedAt($value)
 * @method static Builder<static>|Speaker whereWebsite($value)
 * @method static Builder<static>|Speaker whereXProfile($value)
 * @method static Builder<static>|Speaker whereYoutubeProfile($value)
 *
 * @mixin \Eloquent
 */
class Speaker extends Model
{
    /** @use HasFactory<SpeakerFactory> */
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
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    /**
     * @return BelongsToMany<Talk, $this>
     */
    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class, 'talk_speaker');
    }

    public function showUrl(): Attribute
    {
        return Attribute::get(fn (): string => route('meetups.speakers.show', $this));
    }
}
