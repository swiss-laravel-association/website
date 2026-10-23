<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Head\Enums\OgType;
use Laravel\Head\Enums\TwitterCard;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Head::defaults(fn (HeadBuilder $head): HeadBuilder => $head
            ->title(config('sla.name'), suffix: ' | '.config('sla.name'))
            ->description(config('sla.description'))
            ->canonical()
            ->robots('max-snippet:-1,max-image-preview:large,max-video-preview:-1')
            ->og(type: OgType::Website, siteName: config('sla.name'), locale: 'en_US')
            ->ogImage(asset('images/default-open-graph-image.webp'), alt: config('sla.name'))
            ->twitter(card: TwitterCard::SummaryWithLargeImage, site: '@swisslaravel'));
    }
}
