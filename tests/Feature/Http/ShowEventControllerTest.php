<?php

use App\Models\Event;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('shows the event at its canonical slug-ulid url', function (): void {
    $event = Event::factory()->create(['name' => 'Laravel Zurich Meetup']);

    get($event->show_url)
        ->assertOk()
        ->assertSeeText($event->name);
});

it('redirects a stale slug to the canonical url with a 301', function (): void {
    $event = Event::factory()->create(['name' => 'Laravel Zurich Meetup']);

    get("/events/an-old-slug-{$event->ulid}")
        ->assertStatus(301)
        ->assertRedirect($event->show_url);
});

it('returns 404 for an old integer id', function (): void {
    $event = Event::factory()->create();

    get("/events/{$event->id}")->assertNotFound();
});

it('returns 404 for a non-ulid tail', function (): void {
    get('/events/not-a-ulid')->assertNotFound();
});

it('returns 404 for a valid but unknown ulid', function (): void {
    $orphanUlid = strtolower((string) Str::ulid());

    get("/events/ghost-{$orphanUlid}")->assertNotFound();
});
