<?php

use App\Actions\Events\CreateEvent;
use App\Actions\Events\DeleteEvent;
use App\Actions\Events\PublishEvent;
use App\Actions\Events\UnpublishEvent;
use App\Actions\Events\UpdateEvent;
use App\Models\Event;
use App\Models\Location;
use App\Models\Talk;

it('creates an event and ignores is_published in the input', function (): void {
    $location = Location::factory()->create();

    $event = app(CreateEvent::class)->handle([
        'name' => 'Meetup Zürich',
        'location_id' => $location->id,
        'start_date' => '2026-11-10 18:00:00',
        'end_date' => '2026-11-10 21:00:00',
        'description' => 'Hello',
        'meetup_link' => 'https://example.com/meetup',
        'is_published' => true,
    ]);

    expect($event->exists)->toBeTrue()
        ->and($event->fresh()->is_published)->toBe(0)
        ->and($event->location_id)->toBe($location->id);
});

it('updates only the given keys', function (): void {
    $event = Event::factory()->create(['name' => 'Old', 'description' => 'Keep']);

    $updated = app(UpdateEvent::class)->handle($event, ['name' => 'New']);

    expect($updated->name)->toBe('New')
        ->and($updated->fresh()->description)->toBe('Keep');
});

it('publishes and unpublishes an event', function (): void {
    $event = Event::factory()->create(['is_published' => false]);

    expect(app(PublishEvent::class)->handle($event)->fresh()->is_published)->toBe(1)
        ->and(app(UnpublishEvent::class)->handle($event)->fresh()->is_published)->toBe(0);
});

it('deletes an event and leaves its talks', function (): void {
    $event = Event::factory()->create();
    $talk = Talk::factory()->create();
    $event->talks()->attach($talk);

    app(DeleteEvent::class)->handle($event);

    $this->assertModelMissing($event);
    $this->assertModelExists($talk);
    $this->assertDatabaseCount('event_talk', 0);
});
