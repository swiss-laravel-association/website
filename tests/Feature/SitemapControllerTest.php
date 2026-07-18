<?php

use App\Models\Event;
use App\Models\Post;
use App\Models\Speaker;
use App\Models\Talk;

use function Pest\Laravel\get;

it('serves an XML sitemap', function (): void {
    get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/xml; charset=UTF-8')
        ->assertSee('<urlset', false);
});

it('includes the public static pages', function (): void {
    $response = get(route('sitemap'))->assertOk();

    $response->assertSee(route('home'), false);
    $response->assertSee(route('association.sponsors'), false);
    $response->assertSee(route('blog.index'), false);
    $response->assertSee(route('events.index'), false);
    $response->assertSee(route('imprint'), false);
    $response->assertSee(route('privacy-policy'), false);
});

it('excludes redirect and non-page routes', function (): void {
    $response = get(route('sitemap'))->assertOk();

    $response->assertDontSee(route('links.discord'), false);
    $response->assertDontSee(route('links.newsletter'), false);
    $response->assertDontSee(route('links.feedback'), false);
    $response->assertDontSee(route('meetups.calendar'), false);
    $response->assertDontSee(route('events.next-event'), false);
});

it('includes published posts but not unpublished ones', function (): void {
    $published = Post::factory()->create(['published_at' => now()->subDay()]);
    $unpublished = Post::factory()->create(['published_at' => now()->addDay()]);

    $response = get(route('sitemap'))->assertOk();

    $response->assertSee(route('blog.show', $published->slug), false);
    $response->assertDontSee(route('blog.show', $unpublished->slug), false);
});

it('includes published events but not unpublished ones', function (): void {
    $published = Event::factory()->create();
    $unpublished = Event::factory()->unpublished()->create();

    $response = get(route('sitemap'))->assertOk();

    $response->assertSee($published->show_url, false);
    $response->assertDontSee($unpublished->show_url, false);
});

it('includes talks and speakers', function (): void {
    $talk = Talk::factory()->create();
    $speaker = Speaker::factory()->create();

    $response = get(route('sitemap'))->assertOk();

    $response->assertSee($talk->show_url, false);
    $response->assertSee($speaker->show_url, false);
});
