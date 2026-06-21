<?php

use App\Models\Event;
use App\Models\Location;
use Illuminate\Support\Carbon;

it('renders an upcoming event with its details', function (): void {
    $location = Location::factory()->create([
        'name' => 'Helga',
        'city' => 'Bern',
    ]);

    $event = Event::factory()->create([
        'name' => 'June Meetup in Bern',
        'description' => 'An evening of Laravel talks and pizza.',
        'start_date' => Carbon::now()->addDays(7),
        'end_date' => Carbon::now()->addDays(7)->addHours(3),
        'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/upcoming/',
        'location_id' => $location->id,
    ]);

    $response = $this->get(route('events.show', $event));

    $response->assertStatus(200);
    $response->assertSeeText('June Meetup in Bern');
    $response->assertSeeText('An evening of Laravel talks and pizza.');
    $response->assertSeeText('Helga');
    $response->assertSee($event->meetup_link, false);
});

it('renders a past event without the RSVP button', function (): void {
    $event = Event::factory()
        ->for(Location::factory())
        ->create([
            'name' => 'May Meetup',
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->subMonth()->addHours(3),
            'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/past/',
        ]);

    $response = $this->get(route('events.show', $event));

    $response->assertStatus(200);
    $response->assertSeeText('May Meetup');
    $response->assertDontSee($event->meetup_link, false);
});

it('returns 404 for an unknown event', function (): void {
    $response = $this->get(route('events.show', 999_999));

    $response->assertStatus(404);
});
