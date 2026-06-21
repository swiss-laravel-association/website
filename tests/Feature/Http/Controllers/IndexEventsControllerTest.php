<?php

use App\Models\Event;
use App\Models\Location;
use Illuminate\Support\Carbon;

it('returns a successful response', function (): void {
    $response = $this->get(route('events.index'));

    $response->assertStatus(200);
});

it('shows the next upcoming event and lists other upcoming and past events', function (): void {
    $location = Location::factory()->create();

    $nextEvent = Event::factory()->create([
        'name' => 'June Meetup',
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
        'location_id' => $location->id,
    ]);

    $laterUpcoming = Event::factory()->create([
        'name' => 'July Meetup',
        'start_date' => Carbon::now()->addDays(40),
        'end_date' => Carbon::now()->addDays(40)->addHours(3),
        'location_id' => $location->id,
    ]);

    $pastEvent = Event::factory()->create([
        'name' => 'May Meetup',
        'start_date' => Carbon::now()->subDays(30),
        'end_date' => Carbon::now()->subDays(30)->addHours(3),
        'location_id' => $location->id,
    ]);

    $response = $this->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertSeeText($nextEvent->name);
    $response->assertSeeText($laterUpcoming->name);
    $response->assertSeeText($pastEvent->name);
});

it('does not duplicate the next event in the upcoming events list', function (): void {
    $location = Location::factory()->create();

    $nextEvent = Event::factory()->create([
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
        'location_id' => $location->id,
    ]);

    $response = $this->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertViewHas('nextEvent', fn ($event) => $event->is($nextEvent));
    $response->assertViewHas('upcomingEvents', fn ($events) => $events->isEmpty());
});

it('paginates past events at ten per page', function (): void {
    $location = Location::factory()->create();

    Event::factory()->count(12)->sequence(fn ($sequence) => [
        'start_date' => Carbon::now()->subDays($sequence->index + 1),
        'end_date' => Carbon::now()->subDays($sequence->index + 1)->addHours(3),
        'location_id' => $location->id,
    ])->create();

    $response = $this->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertViewHas('pastEvents', fn ($pastEvents) => $pastEvents->total() === 12
        && $pastEvents->perPage() === 10
        && $pastEvents->count() === 10);
});

it('handles having no events at all', function (): void {
    $response = $this->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertViewHas('nextEvent', null);
});
