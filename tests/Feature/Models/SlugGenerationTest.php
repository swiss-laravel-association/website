<?php

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;

it('generates an event slug from the name', function (): void {
    $event = Event::factory()->create(['name' => 'Laravel Zurich Meetup']);

    expect($event->slug)->toBe('laravel-zurich-meetup');
});

it('generates a talk slug from the title', function (): void {
    $talk = Talk::factory()->create(['title' => 'Scaling Eloquent Queries']);

    expect($talk->slug)->toBe('scaling-eloquent-queries');
});

it('generates a speaker slug from the name', function (): void {
    $speaker = Speaker::factory()->create(['name' => 'Ada Lovelace']);

    expect($speaker->slug)->toBe('ada-lovelace');
});

it('allows duplicate slugs across records', function (): void {
    $a = Talk::factory()->create(['title' => 'Same Title']);
    $b = Talk::factory()->create(['title' => 'Same Title']);

    expect($a->slug)->toBe('same-title')
        ->and($b->slug)->toBe('same-title');
});
