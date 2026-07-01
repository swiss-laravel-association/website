<?php

use App\Models\Event;
use App\Models\Location;
use App\Queries\PastEvents;
use Illuminate\Support\Carbon;

it('includes published events in the past', function (): void {
    $event = Event::factory()->create([
        'start_date' => Carbon::now()->subDays(3),
        'end_date' => Carbon::now()->subDays(3)->addHours(3),
    ]);

    $results = (new PastEvents)->query()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->is($event))->toBeTrue();
});

it('excludes unpublished events', function (): void {
    Event::factory()->unpublished()->create([
        'start_date' => Carbon::now()->subDays(3),
        'end_date' => Carbon::now()->subDays(3)->addHours(3),
    ]);

    expect((new PastEvents)->query()->get())->toBeEmpty();
});

it('excludes upcoming events', function (): void {
    Event::factory()->create([
        'start_date' => Carbon::now()->addDays(3),
        'end_date' => Carbon::now()->addDays(3)->addHours(3),
    ]);

    expect((new PastEvents)->query()->get())->toBeEmpty();
});

it('orders events by start date descending', function (): void {
    $older = Event::factory()->create([
        'start_date' => Carbon::now()->subDays(30),
        'end_date' => Carbon::now()->subDays(30)->addHours(3),
    ]);

    $recent = Event::factory()->create([
        'start_date' => Carbon::now()->subDays(2),
        'end_date' => Carbon::now()->subDays(2)->addHours(3),
    ]);

    $results = (new PastEvents)->query()->get();

    expect($results->pluck('id')->all())->toBe([$recent->id, $older->id]);
});

it('eager loads the location relation', function (): void {
    Event::factory()
        ->for(Location::factory())
        ->create([
            'start_date' => Carbon::now()->subDays(3),
            'end_date' => Carbon::now()->subDays(3)->addHours(3),
        ]);

    $event = (new PastEvents)->query()->first();

    expect($event->relationLoaded('location'))->toBeTrue();
});
