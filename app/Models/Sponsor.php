<?php

namespace App\Models;

use App\Builders\SponsorBuilder;
use App\Enums\SponsorType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property SponsorType $type
 * @property string $name
 * @property string $website
 * @property string|null $background_color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $order
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 *
 * @method static \Database\Factories\SponsorFactory factory($count = null, $state = [])
 * @method static SponsorBuilder<static>|Sponsor founding()
 * @method static SponsorBuilder<static>|Sponsor location()
 * @method static SponsorBuilder<static>|Sponsor newModelQuery()
 * @method static SponsorBuilder<static>|Sponsor newQuery()
 * @method static SponsorBuilder<static>|Sponsor query()
 * @method static SponsorBuilder<static>|Sponsor whereBackgroundColor($value)
 * @method static SponsorBuilder<static>|Sponsor whereCreatedAt($value)
 * @method static SponsorBuilder<static>|Sponsor whereId($value)
 * @method static SponsorBuilder<static>|Sponsor whereName($value)
 * @method static SponsorBuilder<static>|Sponsor whereOrder($value)
 * @method static SponsorBuilder<static>|Sponsor whereType($value)
 * @method static SponsorBuilder<static>|Sponsor whereUpdatedAt($value)
 * @method static SponsorBuilder<static>|Sponsor whereWebsite($value)
 *
 * @mixin \Eloquent
 */
class Sponsor extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\SponsorFactory> */
    use HasFactory;

    use InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'type' => SponsorType::class,
        ];
    }

    /**
     * @return SponsorBuilder<Sponsor>
     */
    #[Override]
    public function newEloquentBuilder($query): SponsorBuilder
    {
        return new SponsorBuilder($query);
    }

    #[Override]
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->registerMediaConversions(function (Media $media): void {
                $this
                    ->addMediaConversion('thumb')
                    ->height(100 * 2);
            });
    }
}
