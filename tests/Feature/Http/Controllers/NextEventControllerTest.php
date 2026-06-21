<?php

use App\Models\Event;
use Illuminate\Support\Carbon;

it('redirects to the meetup link of the next upcoming event', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->addDays(7),
        'end_date' => Carbon::now()->addDays(7)->addHours(3),
        'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/upcoming/',
    ]);

    $response = $this->get(route('events.next-event'));

    $response->assertRedirect('https://www.meetup.com/laravel-switzerland-meetup/events/upcoming/');
});

it('redirects to the soonest upcoming event when several are scheduled', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->addDays(30),
        'end_date' => Carbon::now()->addDays(30)->addHours(3),
        'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/later/',
    ]);

    Event::factory()->create([
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
        'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/soonest/',
    ]);

    $response = $this->get(route('events.next-event'));

    $response->assertRedirect('https://www.meetup.com/laravel-switzerland-meetup/events/soonest/');
});

it('ignores events whose start date is in the past', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->subDays(7),
        'end_date' => Carbon::now()->subDays(7)->addHours(3),
        'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/past/',
    ]);

    $response = $this->get(route('events.next-event'));

    $response->assertRedirect(route('homepage'));
});

it('redirects to the homepage when no upcoming event exists', function (): void {
    $response = $this->get(route('events.next-event'));

    $response->assertRedirect(route('homepage'));
});
