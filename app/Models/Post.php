<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
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

        self::deleting(function (Post $post) {
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

    protected function parsedContent(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => Str::markdown($this->content),
        );
    }

    public function getReadingTimeInMinutes(): int
    {
        return ceil(str_word_count(strip_tags($this->content)) / 200);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id')
            ->withTimestamps();
    }
}
