<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereBlueskyProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereGithubProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereLinkedinProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereXProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Speaker whereYoutubeProfile($value)
 *
 * @mixin \Eloquent
 */
class Speaker extends Model
{
    //
}
