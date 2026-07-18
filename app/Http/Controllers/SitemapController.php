<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Post;
use App\Models\Speaker;
use App\Models\Talk;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke(): Sitemap
    {
        return Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0))
            ->add(route('association.sponsors'))
            ->add(route('blog.index'))
            ->add(route('events.index'))
            ->add(route('imprint'))
            ->add(route('privacy-policy'))
            ->add(Post::query()->where('published_at', '<=', now())->get())
            ->add(Event::query()->published()->get())
            ->add(Talk::query()->get())
            ->add(Speaker::query()->get());
    }
}
