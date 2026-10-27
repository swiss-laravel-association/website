<?php

use App\Models\Event;
use App\Models\Location;

use function Pest\Laravel\get;

it('returns a successful response', function (): void {
    get(route('association.about'))
        ->assertOk()
        ->assertSee('<title>About us | Swiss Laravel Association</title>', false);
});

it('renders breadcrumbs with matching JSON-LD', function (): void {
    get(route('association.about'))
        ->assertOk()
        ->assertSee('data-flux-breadcrumbs', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('"name":"About"', false);
});

it('lists the executive committee', function (): void {
    $response = get(route('association.about'))->assertOk();

    foreach (config('sla.executive_committee') as $member) {
        $response->assertSeeText($member['name'])
            ->assertSeeText($member['role'])
            ->assertSee('https://github.com/'.$member['github'], false);
    }
});

it('counts past published events and their distinct cities', function (): void {
    $zurich = Location::factory()->create(['city' => 'Zürich']);
    $otherZurichVenue = Location::factory()->create(['city' => 'Zürich']);
    $bern = Location::factory()->create(['city' => 'Bern']);

    Event::factory()->create(['location_id' => $zurich->id, 'start_date' => now()->subMonths(3)]);
    Event::factory()->create(['location_id' => $otherZurichVenue->id, 'start_date' => now()->subMonths(2)]);
    Event::factory()->create(['location_id' => $bern->id, 'start_date' => now()->subMonth()]);
    Event::factory()->unpublished()->create(['location_id' => Location::factory()->create(['city' => 'Basel'])->id, 'start_date' => now()->subWeek()]);
    Event::factory()->create(['location_id' => Location::factory()->create(['city' => 'Geneva'])->id, 'start_date' => now()->addMonth()]);

    get(route('association.about'))
        ->assertOk()
        ->assertSeeText('held 3 events in 2 cities');
});

it('links to the general email address for hosting a meetup', function (): void {
    get(route('association.about'))
        ->assertOk()
        ->assertSee('href="mailto:info@laravel.swiss"', false);
});

it('links to the about page from the navigation', function (): void {
    get(route('home'))
        ->assertOk()
        ->assertSee(route('association.about'), false);
});
