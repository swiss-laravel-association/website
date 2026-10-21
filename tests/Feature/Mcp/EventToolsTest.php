<?php

use App\Enums\Ability;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Events\CreateEventTool;
use App\Mcp\Tools\Events\DeleteEventTool;
use App\Mcp\Tools\Events\GetEventTool;
use App\Mcp\Tools\Events\ListEventsTool;
use App\Mcp\Tools\Events\PublishEventTool;
use App\Mcp\Tools\Events\UnpublishEventTool;
use App\Mcp\Tools\Events\UpdateEventTool;
use App\Models\Event;
use App\Models\Location;
use App\Models\Speaker;
use App\Models\Talk;

/**
 * @return array<string, string>
 */
function eventInput(array $overrides = []): array
{
    return [
        'name' => 'November Meetup',
        'description' => 'Talks and pizza.',
        'start_date' => '2026-11-10T18:30:00',
        'end_date' => '2026-11-10T21:30:00',
        ...$overrides,
    ];
}

it('names the event tools in kebab case', function (): void {
    $names = collect([
        ListEventsTool::class,
        GetEventTool::class,
        CreateEventTool::class,
        UpdateEventTool::class,
        DeleteEventTool::class,
        PublishEventTool::class,
        UnpublishEventTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe([
        'list-events',
        'get-event',
        'create-event',
        'update-event',
        'delete-event',
        'publish-event',
        'unpublish-event',
    ]);
});

it('marks delete-event as destructive', function (): void {
    expect((new DeleteEventTool)->annotations())->toMatchArray(['destructiveHint' => true]);
});

it('creates an unpublished event and reads dates without offset as Zurich time', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);

    $response = WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput(['is_published' => true]))
        ->assertOk()
        ->assertHasNoErrors();

    $event = Event::query()->sole();

    expect($event->is_published)->toBeFalse()
        ->and($event->start_date->format('Y-m-d H:i:s'))->toBe('2026-11-10 18:30:00')
        ->and($event->end_date->format('Y-m-d H:i:s'))->toBe('2026-11-10 21:30:00')
        ->and(toolJson($response))->toMatchArray([
            'ulid' => $event->ulid,
            'name' => 'November Meetup',
            'is_published' => false,
            'start_date' => '2026-11-10T18:30:00+01:00',
            'end_date' => '2026-11-10T21:30:00+01:00',
            'url' => $event->show_url,
        ]);
});

it('converts dates with an offset to Zurich time', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput([
        'start_date' => '2027-07-08T16:30:00Z',
        'end_date' => '2027-07-08T21:00:00+02:00',
    ]))->assertOk()->assertHasNoErrors();

    $event = Event::query()->sole();

    expect($event->start_date->format('Y-m-d H:i:s'))->toBe('2027-07-08 18:30:00')
        ->and($event->end_date->format('Y-m-d H:i:s'))->toBe('2027-07-08 21:00:00');
});

it('resolves the location ulid to the location', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);
    $location = Location::factory()->create();

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput(['location_ulid' => $location->ulid]))
        ->assertOk()
        ->assertHasNoErrors();

    expect(Event::query()->sole()->location_id)->toBe($location->id);
});

it('rejects an unknown location ulid', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput(['location_ulid' => '01JZZZZZZZZZZZZZZZZZZZZZZZ']))
        ->assertHasErrors(['No location with this ulid exists.']);

    expect(Event::query()->count())->toBe(0);
});

it('rejects an end date before the start date', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput([
        'start_date' => '2026-11-10T18:30:00',
        'end_date' => '2026-11-10T17:00:00',
    ]))->assertHasErrors(['The end_date must be after the start_date.']);

    expect(Event::query()->count())->toBe(0);
});

it('returns a readable error for unparseable dates', function (string $date): void {
    $user = userWithAbilities(Ability::EventsCreate);

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput(['start_date' => $date]))
        ->assertHasErrors(['The start_date must be an ISO 8601 date-time']);

    expect(Event::query()->count())->toBe(0);
})->with(['next friday', '10.11.2026 18:30', '2026-02-30T18:30:00', '2026-11-10T25:00:00', '2026-11-10']);

it('requires name, description and both dates', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, [])
        ->assertHasErrors(['name', 'description', 'start date', 'end date']);
});

it('denies create without the create ability', function (): void {
    $user = userWithAbilities(Ability::EventsView, Ability::EventsUpdate);

    WebsiteServer::actingAs($user)->tool(CreateEventTool::class, eventInput())
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Event::query()->count())->toBe(0);
});

it('lists events', function (): void {
    $user = userWithAbilities(Ability::EventsView);
    $events = Event::factory()->count(2)->create();

    WebsiteServer::actingAs($user)->tool(ListEventsTool::class)
        ->assertOk()
        ->assertSee($events->pluck('ulid')->all());
});

it('denies list without the view ability', function (): void {
    $user = userWithAbilities(Ability::EventsCreate);

    WebsiteServer::actingAs($user)->tool(ListEventsTool::class)
        ->assertHasErrors(['This action is unauthorized.']);
});

it('gets an event with its location, talks and speakers', function (): void {
    $user = userWithAbilities(Ability::EventsView);
    $location = Location::factory()->create(['name' => 'Kosmos']);
    $event = Event::factory()->create(['location_id' => $location->id]);
    $talk = Talk::factory()->create(['title' => 'Octane']);
    $speaker = Speaker::factory()->create(['name' => 'Jane Doe']);
    $talk->speakers()->attach($speaker);
    $event->talks()->attach($talk);

    $payload = toolJson(
        WebsiteServer::actingAs($user)->tool(GetEventTool::class, ['ulid' => $event->ulid])->assertOk()->assertHasNoErrors()
    );

    expect($payload['ulid'])->toBe($event->ulid)
        ->and($payload['location']['name'])->toBe('Kosmos')
        ->and($payload['talks'])->toHaveCount(1)
        ->and($payload['talks'][0]['title'])->toBe('Octane')
        ->and($payload['talks'][0])->not->toHaveKey('pivot')
        ->and($payload['talks'][0]['speakers'][0]['name'])->toBe('Jane Doe');
});

it('returns an error for an unknown event', function (): void {
    $user = userWithAbilities(Ability::EventsView, Ability::EventsUpdate, Ability::EventsDelete, Ability::EventsPublish);
    $ulid = '01JZZZZZZZZZZZZZZZZZZZZZZZ';

    foreach ([GetEventTool::class, UpdateEventTool::class, DeleteEventTool::class, PublishEventTool::class, UnpublishEventTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, ['ulid' => $ulid])
            ->assertHasErrors(["Event [{$ulid}] not found."]);
    }
});

it('requires a ulid on get, update, delete, publish and unpublish', function (): void {
    $user = userWithAbilities(Ability::EventsView, Ability::EventsUpdate, Ability::EventsDelete, Ability::EventsPublish);

    foreach ([GetEventTool::class, UpdateEventTool::class, DeleteEventTool::class, PublishEventTool::class, UnpublishEventTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, [])->assertHasErrors(['ulid']);
    }
});

it('denies get without the view ability', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $event = Event::factory()->create();

    WebsiteServer::actingAs($user)->tool(GetEventTool::class, ['ulid' => $event->ulid])
        ->assertHasErrors(['This action is unauthorized.']);
});

it('updates an event', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $event = Event::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, [
        'ulid' => $event->ulid,
        'name' => 'New',
        'start_date' => '2026-12-01T18:00',
        'end_date' => '2026-12-01T21:00',
    ])->assertOk()->assertHasNoErrors()->assertSee('"name":"New"');

    $event->refresh();

    expect($event->name)->toBe('New')
        ->and($event->start_date->format('Y-m-d H:i:s'))->toBe('2026-12-01 18:00:00')
        ->and($event->end_date->format('Y-m-d H:i:s'))->toBe('2026-12-01 21:00:00');
});

it('compares a single updated date with the stored other date', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $event = Event::factory()->create([
        'start_date' => '2026-11-10 18:30:00',
        'end_date' => '2026-11-10 21:30:00',
    ]);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, ['ulid' => $event->ulid, 'start_date' => '2026-11-10T22:00:00'])
        ->assertHasErrors(['The end_date must be after the start_date.']);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, ['ulid' => $event->ulid, 'end_date' => '2026-11-10T18:00:00'])
        ->assertHasErrors(['The end_date must be after the start_date.']);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, ['ulid' => $event->ulid, 'end_date' => '2026-11-10T22:00:00'])
        ->assertOk()
        ->assertHasNoErrors();

    $event->refresh();

    expect($event->start_date->format('Y-m-d H:i:s'))->toBe('2026-11-10 18:30:00')
        ->and($event->end_date->format('Y-m-d H:i:s'))->toBe('2026-11-10 22:00:00');
});

it('sets and clears the location on update', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $location = Location::factory()->create();
    $event = Event::factory()->create();

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, ['ulid' => $event->ulid, 'location_ulid' => $location->ulid])
        ->assertHasNoErrors();

    expect($event->refresh()->location_id)->toBe($location->id);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, ['ulid' => $event->ulid, 'location_ulid' => null])
        ->assertHasNoErrors();

    expect($event->refresh()->location_id)->toBeNull();
});

it('ignores is_published and other keys without validation rules on update', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $event = Event::factory()->unpublished()->create(['name' => 'Old', 'slug' => 'old']);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, [
        'ulid' => $event->ulid,
        'name' => 'New',
        'is_published' => true,
        'slug' => 'hacked',
        'location_id' => 9999,
    ])->assertOk()->assertHasNoErrors();

    $event->refresh();

    expect($event->name)->toBe('New')
        ->and($event->is_published)->toBeFalse()
        ->and($event->slug)->not->toBe('hacked')
        ->and($event->location_id)->toBeNull();
});

it('denies update without the update ability', function (): void {
    $user = userWithAbilities(Ability::EventsView);
    $event = Event::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateEventTool::class, ['ulid' => $event->ulid, 'name' => 'New'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($event->refresh()->name)->toBe('Old');
});

it('deletes an event', function (): void {
    $user = userWithAbilities(Ability::EventsDelete);
    $event = Event::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteEventTool::class, ['ulid' => $event->ulid])
        ->assertOk()
        ->assertSee(['"deleted":true', $event->ulid]);

    expect(Event::query()->count())->toBe(0);
});

it('denies delete without the delete ability', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $event = Event::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteEventTool::class, ['ulid' => $event->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Event::query()->count())->toBe(1);
});

it('publishes an event only with the publish ability', function (): void {
    $event = Event::factory()->unpublished()->create();

    WebsiteServer::actingAs(userWithAbilities(Ability::EventsUpdate))->tool(PublishEventTool::class, ['ulid' => $event->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($event->refresh()->is_published)->toBeFalse();

    WebsiteServer::actingAs(userWithAbilities(Ability::EventsPublish))->tool(PublishEventTool::class, ['ulid' => $event->ulid])
        ->assertOk()
        ->assertHasNoErrors()
        ->assertSee('"is_published":true');

    expect($event->refresh()->is_published)->toBeTrue();
});

it('unpublishes an event only with the publish ability', function (): void {
    $event = Event::factory()->create(['is_published' => true]);

    WebsiteServer::actingAs(userWithAbilities(Ability::EventsUpdate))->tool(UnpublishEventTool::class, ['ulid' => $event->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($event->refresh()->is_published)->toBeTrue();

    WebsiteServer::actingAs(userWithAbilities(Ability::EventsPublish))->tool(UnpublishEventTool::class, ['ulid' => $event->ulid])
        ->assertOk()
        ->assertHasNoErrors()
        ->assertSee('"is_published":false');

    expect($event->refresh()->is_published)->toBeFalse();
});
