<?php

use App\Enums\Ability;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Events\CreateEventTool;
use App\Mcp\Tools\Events\GetEventTool;
use App\Mcp\Tools\Linking\AttachSpeakerToTalkTool;
use App\Mcp\Tools\Linking\AttachTalkToEventTool;
use App\Mcp\Tools\Linking\DetachSpeakerFromTalkTool;
use App\Mcp\Tools\Linking\DetachTalkFromEventTool;
use App\Mcp\Tools\Locations\CreateLocationTool;
use App\Mcp\Tools\Speakers\CreateSpeakerTool;
use App\Mcp\Tools\Talks\CreateTalkTool;
use App\Models\Event;
use App\Models\Location;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\User;

it('names the linking tools in kebab case', function (): void {
    $names = collect([
        AttachTalkToEventTool::class,
        DetachTalkFromEventTool::class,
        AttachSpeakerToTalkTool::class,
        DetachSpeakerFromTalkTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe([
        'attach-talk-to-event',
        'detach-talk-from-event',
        'attach-speaker-to-talk',
        'detach-speaker-from-talk',
    ]);
});

it('attaches a talk to an event once with the events:update ability', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate);
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();

    foreach ([1, 2] as $ignored) {
        WebsiteServer::actingAs($user)->tool(AttachTalkToEventTool::class, ['talk_ulid' => $talk->ulid, 'event_ulid' => $event->ulid])
            ->assertOk()
            ->assertHasNoErrors()
            ->assertSee(['"attached":true', $talk->ulid, $event->ulid]);
    }

    $this->assertDatabaseCount('event_talk', 1);
});

it('denies attaching a talk to an event without the events:update ability', function (): void {
    $user = userWithAbilities(Ability::TalksUpdate, Ability::EventsView);
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();

    WebsiteServer::actingAs($user)->tool(AttachTalkToEventTool::class, ['talk_ulid' => $talk->ulid, 'event_ulid' => $event->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    $this->assertDatabaseCount('event_talk', 0);
});

it('detaches a talk from an event', function (): void {
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();
    $event->talks()->attach($talk);

    WebsiteServer::actingAs(userWithAbilities(Ability::TalksUpdate))
        ->tool(DetachTalkFromEventTool::class, ['talk_ulid' => $talk->ulid, 'event_ulid' => $event->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    $this->assertDatabaseCount('event_talk', 1);

    WebsiteServer::actingAs(userWithAbilities(Ability::EventsUpdate))
        ->tool(DetachTalkFromEventTool::class, ['talk_ulid' => $talk->ulid, 'event_ulid' => $event->ulid])
        ->assertOk()
        ->assertHasNoErrors()
        ->assertSee('"detached":true');

    $this->assertDatabaseCount('event_talk', 0);
    $this->assertModelExists($talk);
});

it('attaches a speaker to a talk once with the talks:update ability', function (): void {
    $user = userWithAbilities(Ability::TalksUpdate);
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();

    foreach ([1, 2] as $ignored) {
        WebsiteServer::actingAs($user)->tool(AttachSpeakerToTalkTool::class, ['speaker_ulid' => $speaker->ulid, 'talk_ulid' => $talk->ulid])
            ->assertOk()
            ->assertHasNoErrors()
            ->assertSee(['"attached":true', $speaker->ulid, $talk->ulid]);
    }

    $this->assertDatabaseCount('talk_speaker', 1);
});

it('denies attaching a speaker to a talk without the talks:update ability', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate, Ability::SpeakersUpdate);
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();

    WebsiteServer::actingAs($user)->tool(AttachSpeakerToTalkTool::class, ['speaker_ulid' => $speaker->ulid, 'talk_ulid' => $talk->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    $this->assertDatabaseCount('talk_speaker', 0);
});

it('detaches a speaker from a talk', function (): void {
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();
    $talk->speakers()->attach($speaker);

    WebsiteServer::actingAs(userWithAbilities(Ability::SpeakersUpdate))
        ->tool(DetachSpeakerFromTalkTool::class, ['speaker_ulid' => $speaker->ulid, 'talk_ulid' => $talk->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    $this->assertDatabaseCount('talk_speaker', 1);

    WebsiteServer::actingAs(userWithAbilities(Ability::TalksUpdate))
        ->tool(DetachSpeakerFromTalkTool::class, ['speaker_ulid' => $speaker->ulid, 'talk_ulid' => $talk->ulid])
        ->assertOk()
        ->assertHasNoErrors()
        ->assertSee('"detached":true');

    $this->assertDatabaseCount('talk_speaker', 0);
    $this->assertModelExists($speaker);
});

it('returns not-found errors for unknown ulids on either side', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate, Ability::TalksUpdate);
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();
    $speaker = Speaker::factory()->create();
    $unknown = '01JZZZZZZZZZZZZZZZZZZZZZZZ';

    foreach ([AttachTalkToEventTool::class, DetachTalkFromEventTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, ['talk_ulid' => $unknown, 'event_ulid' => $event->ulid])
            ->assertHasErrors(["Talk [{$unknown}] not found."]);
        WebsiteServer::actingAs($user)->tool($tool, ['talk_ulid' => $talk->ulid, 'event_ulid' => $unknown])
            ->assertHasErrors(["Event [{$unknown}] not found."]);
    }

    foreach ([AttachSpeakerToTalkTool::class, DetachSpeakerFromTalkTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, ['speaker_ulid' => $unknown, 'talk_ulid' => $talk->ulid])
            ->assertHasErrors(["Speaker [{$unknown}] not found."]);
        WebsiteServer::actingAs($user)->tool($tool, ['speaker_ulid' => $speaker->ulid, 'talk_ulid' => $unknown])
            ->assertHasErrors(["Talk [{$unknown}] not found."]);
    }
});

it('requires both ulids', function (): void {
    $user = userWithAbilities(Ability::EventsUpdate, Ability::TalksUpdate);

    foreach ([AttachTalkToEventTool::class, DetachTalkFromEventTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, [])->assertHasErrors(['talk ulid', 'event ulid']);
    }

    foreach ([AttachSpeakerToTalkTool::class, DetachSpeakerFromTalkTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, [])->assertHasErrors(['speaker ulid', 'talk ulid']);
    }
});

it('builds a full event through the tools', function (): void {
    $user = userWithAbilities(
        Ability::LocationsCreate,
        Ability::EventsCreate,
        Ability::EventsView,
        Ability::EventsUpdate,
        Ability::SpeakersCreate,
        Ability::TalksCreate,
        Ability::TalksUpdate,
    );

    $server = WebsiteServer::actingAs($user);

    $server->tool(CreateLocationTool::class, ['name' => 'Kosmos', 'zip_code' => '8004', 'city' => 'Zürich'])->assertHasNoErrors();
    $location = Location::query()->sole();

    $server->tool(CreateEventTool::class, [
        'name' => 'November Meetup',
        'description' => 'Talks and pizza.',
        'start_date' => '2026-11-10T18:30:00',
        'end_date' => '2026-11-10T21:30:00',
        'location_ulid' => $location->ulid,
    ])->assertHasNoErrors();
    $event = Event::query()->sole();

    $server->tool(CreateSpeakerTool::class, ['name' => 'Jane Doe'])->assertHasNoErrors();
    $speaker = Speaker::query()->sole();

    $server->tool(CreateTalkTool::class, ['title' => 'Octane in Production', 'description' => 'A talk.'])->assertHasNoErrors();
    $talk = Talk::query()->sole();

    $server->tool(AttachSpeakerToTalkTool::class, ['speaker_ulid' => $speaker->ulid, 'talk_ulid' => $talk->ulid])->assertHasNoErrors();
    $server->tool(AttachTalkToEventTool::class, ['talk_ulid' => $talk->ulid, 'event_ulid' => $event->ulid])->assertHasNoErrors();

    $payload = toolJson($server->tool(GetEventTool::class, ['ulid' => $event->ulid])->assertOk()->assertHasNoErrors());

    expect($payload)->toMatchArray([
        'id' => $event->id,
        'ulid' => $event->ulid,
        'name' => 'November Meetup',
        'description' => 'Talks and pizza.',
        'start_date' => '2026-11-10T18:30:00+01:00',
        'end_date' => '2026-11-10T21:30:00+01:00',
        'is_published' => false,
        'location_id' => $location->id,
        'url' => $event->show_url,
    ])
        ->and($payload['location'])->toMatchArray([
            'id' => $location->id,
            'ulid' => $location->ulid,
            'name' => 'Kosmos',
            'zip_code' => '8004',
            'city' => 'Zürich',
        ])
        ->and($payload['talks'])->toHaveCount(1)
        ->and($payload['talks'][0])->toMatchArray([
            'id' => $talk->id,
            'ulid' => $talk->ulid,
            'title' => 'Octane in Production',
            'url' => $talk->show_url,
        ])
        ->and($payload['talks'][0]['speakers'])->toHaveCount(1)
        ->and($payload['talks'][0]['speakers'][0])->toMatchArray([
            'id' => $speaker->id,
            'ulid' => $speaker->ulid,
            'name' => 'Jane Doe',
        ]);
});

it('registers the event, talk and linking tools on the server', function (): void {
    $token = User::factory()->create()->createToken('t', [Ability::EventsView->value])->plainTextToken;
    $names = [];
    $cursor = null;

    do {
        $response = $this->postJson('/mcp', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => $cursor === null ? (object) [] : ['cursor' => $cursor],
        ], ['Authorization' => "Bearer {$token}"])->assertOk();

        $names = [...$names, ...$response->json('result.tools.*.name')];
        $cursor = $response->json('result.nextCursor');
    } while ($cursor !== null);

    expect($names)->toContain(
        'list-events', 'get-event', 'create-event', 'update-event', 'delete-event', 'publish-event', 'unpublish-event',
        'list-talks', 'get-talk', 'create-talk', 'update-talk', 'delete-talk',
        'attach-talk-to-event', 'detach-talk-from-event', 'attach-speaker-to-talk', 'detach-speaker-from-talk',
    );
});
