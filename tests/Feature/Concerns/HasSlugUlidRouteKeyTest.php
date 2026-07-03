<?php

use App\Models\Event;
use Illuminate\Support\Str;

it('builds a route key of slug and ulid', function (): void {
    $event = Event::factory()->create(['name' => 'Laravel Zurich Meetup']);

    expect($event->getRouteKey())->toBe("laravel-zurich-meetup-{$event->ulid}");
});

it('resolves a route binding by the trailing ulid, ignoring the slug', function (): void {
    $event = Event::factory()->create();

    $resolved = (new Event)->resolveRouteBinding("any-slug-at-all-{$event->ulid}");

    expect($resolved)->not->toBeNull()
        ->and($resolved->id)->toBe($event->id);
});

it('returns null when the trailing token is not a ulid', function (): void {
    Event::factory()->create();

    expect((new Event)->resolveRouteBinding('not-a-ulid'))->toBeNull()
        ->and((new Event)->resolveRouteBinding('5'))->toBeNull();
});

it('returns null when the ulid is valid but no record matches', function (): void {
    $orphanUlid = strtolower((string) Str::ulid());

    expect((new Event)->resolveRouteBinding("ghost-{$orphanUlid}"))->toBeNull();
});

it('resolves an uppercase ulid to the same record', function (): void {
    $event = Event::factory()->create();

    $resolved = (new Event)->resolveRouteBinding('slug-'.strtoupper($event->ulid));

    expect($resolved)->not->toBeNull()
        ->and($resolved->id)->toBe($event->id);
});
