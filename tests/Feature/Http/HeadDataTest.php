<?php

use App\Models\Event;
use App\Models\Location;
use App\Models\Post;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Support\Carbon;

use function Pest\Laravel\get;

/**
 * @return array<int, array<string, mixed>>
 */
function jsonLdBlocks(string $html): array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    return array_map(fn (string $json): array => json_decode($json, true, flags: JSON_THROW_ON_ERROR), $matches[1]);
}

/**
 * @return array<string, mixed>|null
 */
function jsonLdOfType(string $html, string $type, ?string $name = null): ?array
{
    foreach (jsonLdBlocks($html) as $block) {
        if ($block['@type'] === $type && ($name === null || ($block['name'] ?? null) === $name)) {
            return $block;
        }
    }

    return null;
}

it('renders default head tags on the homepage', function (): void {
    get(route('home'))
        ->assertOk()
        ->assertSee('<title>Swiss Laravel Association</title>', false)
        ->assertSee('rel="canonical"', false)
        ->assertSee('property="og:site_name"', false)
        ->assertSee('property="og:image"', false)
        ->assertSee('name="twitter:card"', false);
});

it('marks the imprint and privacy policy as noindex', function (): void {
    get(route('imprint'))->assertOk()->assertSee('noindex', false);
    get(route('privacy-policy'))->assertOk()->assertSee('noindex', false);
});

it('renders head tags on the blog pages', function (): void {
    $post = Post::factory()->create([
        'title' => 'Hello Switzerland',
        'excerpt' => 'A short introduction.',
        'published_at' => Carbon::now()->subDay(),
    ]);

    get(route('blog.index'))
        ->assertOk()
        ->assertSee('<title>Blog | Swiss Laravel Association</title>', false);

    get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertSee('<title>Hello Switzerland | Swiss Laravel Association</title>', false)
        ->assertSee('content="A short introduction."', false)
        ->assertSee('article:published_time', false);
});

it('renders an Event schema with location, talks and speakers on the event page', function (): void {
    $event = Event::factory()
        ->for(Location::factory(['name' => 'Helga', 'city' => 'Bern', 'zip_code' => '3011']))
        ->create(['name' => 'June Meetup in Bern']);

    $talk = Talk::factory()->create(['title' => 'Scaling Eloquent']);
    $talk->speakers()->attach(Speaker::factory()->create(['name' => 'Ada Lovelace']));
    $event->talks()->attach($talk);

    $schema = jsonLdOfType(get($event->show_url)->assertOk()->getContent(), 'Event', 'June Meetup in Bern');

    expect($schema)
        ->not->toBeNull()
        ->and($schema['url'])->toBe($event->show_url)
        ->and($schema['location']['name'])->toBe('Helga')
        ->and($schema['location']['address']['addressLocality'])->toBe('Bern')
        ->and($schema['subEvent'][0]['name'])->toBe('Scaling Eloquent')
        ->and($schema['performer'][0]['name'])->toBe('Ada Lovelace');
});

it('renders ItemList schemas on the events index and homepage', function (): void {
    Event::factory()->create(['name' => 'Next Meetup', 'start_date' => Carbon::now()->addWeek(), 'end_date' => Carbon::now()->addWeek()->addHours(3)]);
    Event::factory()->create(['name' => 'Old Meetup', 'start_date' => Carbon::now()->subMonth(), 'end_date' => Carbon::now()->subMonth()->addHours(3)]);

    $index = get(route('events.index'))->assertOk()->getContent();

    expect(jsonLdOfType($index, 'ItemList', 'Upcoming meetups')['itemListElement'][0]['item']['name'])->toBe('Next Meetup')
        ->and(jsonLdOfType($index, 'ItemList', 'Past meetups')['itemListElement'][0]['item']['name'])->toBe('Old Meetup');

    $home = get(route('home'))->assertOk()->getContent();

    expect(jsonLdOfType($home, 'ItemList', 'Upcoming meetups')['numberOfItems'])->toBe(1);
});

it('renders a CreativeWork schema on the talk page', function (): void {
    $talk = Talk::factory()->create(['title' => 'Scaling Eloquent', 'description' => 'How to scale.']);
    $talk->speakers()->attach(Speaker::factory()->create(['name' => 'Ada Lovelace']));
    $talk->events()->attach(Event::factory()->create(['name' => 'June Meetup']));

    $response = get($talk->show_url)
        ->assertOk()
        ->assertSee('<title>Scaling Eloquent | Swiss Laravel Association</title>', false);

    $schema = jsonLdOfType($response->getContent(), 'CreativeWork', 'Scaling Eloquent');

    expect($schema['author'][0]['name'])->toBe('Ada Lovelace')
        ->and($schema['isPartOf'][0]['name'])->toBe('June Meetup');
});

it('renders ProfilePage and talk list schemas on the speaker page', function (): void {
    $speaker = Speaker::factory()->create([
        'name' => 'Ada Lovelace',
        'bio' => 'Mathematician.',
        'website' => 'https://example.com',
        'github_profile' => null,
        'x_profile' => null,
        'linkedin_profile' => null,
        'bluesky_profile' => null,
    ]);
    $speaker->talks()->attach(Talk::factory()->create(['title' => 'Analytical Engines']));

    $response = get($speaker->show_url)
        ->assertOk()
        ->assertSee('<title>Ada Lovelace | Swiss Laravel Association</title>', false);

    $profile = jsonLdOfType($response->getContent(), 'ProfilePage');
    $talks = jsonLdOfType($response->getContent(), 'ItemList', 'Talks by Ada Lovelace');

    expect($profile['mainEntity']['name'])->toBe('Ada Lovelace')
        ->and($profile['mainEntity']['sameAs'])->toBe(['https://example.com'])
        ->and($talks['itemListElement'][0]['item']['name'])->toBe('Analytical Engines');
});

it('renders a speaker without optional profile fields', function (): void {
    $speaker = Speaker::factory()->create([
        'bio' => null,
        'website' => null,
        'github_profile' => null,
        'x_profile' => null,
        'linkedin_profile' => null,
        'bluesky_profile' => null,
    ]);

    get($speaker->show_url)->assertOk();
});

it('renders the Organization schema on every page', function (): void {
    $schema = jsonLdOfType(get(route('home'))->assertOk()->getContent(), 'Organization');

    expect($schema['name'])->toBe(config('sla.name'))
        ->and($schema['legalName'])->toBe(config('sla.legal_name'))
        ->and($schema['address']['addressCountry'])->toBe('CH')
        ->and($schema['sameAs'])->toBe(array_values(config('sla.socials')));
});

it('renders a BreadcrumbList schema in the head that falls back to the current page for the last crumb', function (): void {
    $html = get(route('events.index'))->assertOk()->getContent();

    $schema = jsonLdOfType($html, 'BreadcrumbList');

    expect($schema['itemListElement'])->toHaveCount(2)
        ->and($schema['itemListElement'][0])->toMatchArray(['position' => 1, 'name' => 'Home', 'item' => route('home')])
        ->and($schema['itemListElement'][1])->toMatchArray(['position' => 2, 'name' => 'Events', 'item' => route('events.index')])
        ->and(strpos($html, 'BreadcrumbList'))->toBeLessThan(strpos($html, '</head>'));
});
