<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

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
 * @property-read Collection<int, \App\Models\Talk> $talks
 * @property-read int|null $talks_count
 *
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
 * @method static Builder<static>|Speaker whereUpdatedAt($value)
 * @method static Builder<static>|Speaker whereWebsite($value)
 * @method static Builder<static>|Speaker whereXProfile($value)
 * @method static Builder<static>|Speaker whereYoutubeProfile($value)
 *
 * @mixin \Eloquent
 */
class Speaker extends Model
{
    /**
     * @return BelongsToMany<Talk, $this>
     */
    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class, 'talk_speaker');
    }
}
