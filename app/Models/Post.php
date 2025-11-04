<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $excerpt
 * @property CarbonImmutable|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\User> $authors
 * @property-read int|null $authors_count
 * @property-read mixed $parsed_content
 * @property-read SEO $seo
 *
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static Builder<static>|Post newModelQuery()
 * @method static Builder<static>|Post newQuery()
 * @method static Builder<static>|Post query()
 * @method static Builder<static>|Post whereContent($value)
 * @method static Builder<static>|Post whereCreatedAt($value)
 * @method static Builder<static>|Post whereExcerpt($value)
 * @method static Builder<static>|Post whereId($value)
 * @method static Builder<static>|Post wherePublishedAt($value)
 * @method static Builder<static>|Post whereSlug($value)
 * @method static Builder<static>|Post whereTitle($value)
 * @method static Builder<static>|Post whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use HasSEO;
    use HasSlug;

    protected function casts(): array
    {
        return [
            'published_at' => 'immutable_datetime',
        ];
    }

    protected static function booting()
    {
        parent::booting();

        self::deleting(function (Post $post): void {
            $post->authors()->detach();
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            // image: $this->getFirstMedia('og_image')?->getUrl(),
        );
    }

    protected function parsedContent(): Attribute // @phpstan-ignore-line
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => Str::markdown($this->content),
        );
    }

    public function getReadingTimeInMinutes(): int
    {
        return (int) ceil(str_word_count(strip_tags($this->content)) / 200);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id')
            ->withTimestamps();
    }
}
