<?php

use App\Models\Event;
use App\Models\Location;
use App\Queries\UpcomingEvents;
use Illuminate\Support\Carbon;

it('includes published events in the future', function (): void {
    $event = Event::factory()->create([
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
    ]);

    $results = (new UpcomingEvents)->query()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->is($event))->toBeTrue();
});

it('excludes unpublished events', function (): void {
    Event::factory()->unpublished()->create([
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
    ]);

    expect((new UpcomingEvents)->query()->get())->toBeEmpty();
});

it('excludes past events', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->subDays(3),
        'end_date' => Carbon::now()->subDays(3)->addHours(3),
    ]);

    expect((new UpcomingEvents)->query()->get())->toBeEmpty();
});

it('orders events by start date ascending', function (): void {
    $later = Event::factory()->create([
        'start_date' => Carbon::now()->addDays(10),
        'end_date' => Carbon::now()->addDays(10)->addHours(3),
    ]);

    $sooner = Event::factory()->create([
        'start_date' => Carbon::now()->addDays(2),
        'end_date' => Carbon::now()->addDays(2)->addHours(3),
    ]);

    $results = (new UpcomingEvents)->query()->get();

    expect($results->pluck('id')->all())->toBe([$sooner->id, $later->id]);
});

it('eager loads the location relation', function (): void {
    Event::factory()
        ->for(Location::factory())
        ->create([
            'start_date' => Carbon::now()->addDays(3),
            'end_date' => Carbon::now()->addDays(3)->addHours(3),
        ]);

    $event = (new UpcomingEvents)->query()->first();

    expect($event->relationLoaded('location'))->toBeTrue();
});
