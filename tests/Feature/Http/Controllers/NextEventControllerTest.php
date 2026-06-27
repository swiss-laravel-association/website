<?php

use App\Models\Event;
use Illuminate\Support\Carbon;

it('redirects to the event detail page of the next upcoming event', function (): void {
    $event = Event::factory()->create([
        'start_date' => Carbon::now()->addDays(7),
        'end_date' => Carbon::now()->addDays(7)->addHours(3),
    ]);

    $response = $this->get(route('events.next-event'));

    $response->assertRedirect(route('events.show', $event));
});

it('redirects to the soonest upcoming event when several are scheduled', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->addDays(30),
        'end_date' => Carbon::now()->addDays(30)->addHours(3),
    ]);

    $soonest = Event::factory()->create([
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
    ]);

    $response = $this->get(route('events.next-event'));

    $response->assertRedirect(route('events.show', $soonest));
});

it('ignores events whose start date is in the past', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->subDays(7),
        'end_date' => Carbon::now()->subDays(7)->addHours(3),
    ]);

    $response = $this->get(route('events.next-event'));

    $response->assertRedirect(route('home'));
});

it('redirects to the homepage when no upcoming event exists', function (): void {
    $response = $this->get(route('events.next-event'));

    $response->assertRedirect(route('home'));
});
