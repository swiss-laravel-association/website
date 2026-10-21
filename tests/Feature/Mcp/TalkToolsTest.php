<?php

use App\Enums\Ability;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Talks\CreateTalkTool;
use App\Mcp\Tools\Talks\DeleteTalkTool;
use App\Mcp\Tools\Talks\GetTalkTool;
use App\Mcp\Tools\Talks\ListTalksTool;
use App\Mcp\Tools\Talks\UpdateTalkTool;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;

it('names the talk tools in kebab case', function (): void {
    $names = collect([
        ListTalksTool::class,
        GetTalkTool::class,
        CreateTalkTool::class,
        UpdateTalkTool::class,
        DeleteTalkTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe(['list-talks', 'get-talk', 'create-talk', 'update-talk', 'delete-talk']);
});

it('marks delete-talk as destructive', function (): void {
    expect((new DeleteTalkTool)->annotations())->toMatchArray(['destructiveHint' => true]);
});

it('creates a talk with the create ability', function (): void {
    $user = userWithAbilities(Ability::TalksCreate);

    $response = WebsiteServer::actingAs($user)->tool(CreateTalkTool::class, [
        'title' => 'Octane in Production',
        'description' => 'A talk.',
        'recording_url' => 'https://example.com/video',
    ])->assertOk()->assertHasNoErrors();

    $talk = Talk::query()->sole();

    expect($talk->title)->toBe('Octane in Production')
        ->and(toolJson($response))->toMatchArray([
            'ulid' => $talk->ulid,
            'title' => 'Octane in Production',
            'url' => $talk->show_url,
        ]);
});

it('requires a title and description', function (): void {
    $user = userWithAbilities(Ability::TalksCreate);

    WebsiteServer::actingAs($user)->tool(CreateTalkTool::class, ['recording_url' => 'https://example.com'])
        ->assertHasErrors(['title', 'description']);

    expect(Talk::query()->count())->toBe(0);
});

it('requires a valid recording url', function (): void {
    $user = userWithAbilities(Ability::TalksCreate);

    WebsiteServer::actingAs($user)->tool(CreateTalkTool::class, ['title' => 'T', 'description' => 'D', 'recording_url' => 'not a url'])
        ->assertHasErrors(['recording url']);
});

it('denies create without the create ability', function (): void {
    $user = userWithAbilities(Ability::TalksView);

    WebsiteServer::actingAs($user)->tool(CreateTalkTool::class, ['title' => 'T', 'description' => 'D'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Talk::query()->count())->toBe(0);
});

it('lists talks', function (): void {
    $user = userWithAbilities(Ability::TalksView);
    $talks = Talk::factory()->count(3)->create();

    WebsiteServer::actingAs($user)->tool(ListTalksTool::class)
        ->assertOk()
        ->assertSee($talks->pluck('ulid')->all());
});

it('denies list without the view ability', function (): void {
    $user = userWithAbilities(Ability::TalksCreate);

    WebsiteServer::actingAs($user)->tool(ListTalksTool::class)
        ->assertHasErrors(['This action is unauthorized.']);
});

it('gets a talk with its speakers and events', function (): void {
    $user = userWithAbilities(Ability::TalksView);
    $talk = Talk::factory()->create(['title' => 'Octane']);
    $speaker = Speaker::factory()->create(['name' => 'Jane Doe']);
    $event = Event::factory()->create(['name' => 'November Meetup']);
    $talk->speakers()->attach($speaker);
    $talk->events()->attach($event);

    $payload = toolJson(
        WebsiteServer::actingAs($user)->tool(GetTalkTool::class, ['ulid' => $talk->ulid])->assertOk()->assertHasNoErrors()
    );

    expect($payload['title'])->toBe('Octane')
        ->and($payload['speakers'][0]['name'])->toBe('Jane Doe')
        ->and($payload['events'][0]['name'])->toBe('November Meetup')
        ->and($payload['events'][0])->not->toHaveKey('pivot');
});

it('returns an error for an unknown talk', function (): void {
    $user = userWithAbilities(Ability::TalksView, Ability::TalksUpdate, Ability::TalksDelete);
    $ulid = '01JZZZZZZZZZZZZZZZZZZZZZZZ';

    foreach ([GetTalkTool::class, UpdateTalkTool::class, DeleteTalkTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, ['ulid' => $ulid])
            ->assertHasErrors(["Talk [{$ulid}] not found."]);
    }
});

it('requires a ulid on get, update and delete', function (): void {
    $user = userWithAbilities(Ability::TalksView, Ability::TalksUpdate, Ability::TalksDelete);

    foreach ([GetTalkTool::class, UpdateTalkTool::class, DeleteTalkTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, [])->assertHasErrors(['ulid']);
    }
});

it('denies get without the view ability', function (): void {
    $user = userWithAbilities(Ability::TalksUpdate);
    $talk = Talk::factory()->create();

    WebsiteServer::actingAs($user)->tool(GetTalkTool::class, ['ulid' => $talk->ulid])
        ->assertHasErrors(['This action is unauthorized.']);
});

it('updates a talk and ignores keys without validation rules', function (): void {
    $user = userWithAbilities(Ability::TalksUpdate);
    $talk = Talk::factory()->create(['title' => 'Old', 'description' => 'Keep', 'slug' => 'old']);

    WebsiteServer::actingAs($user)->tool(UpdateTalkTool::class, [
        'ulid' => $talk->ulid,
        'title' => 'New',
        'slug' => 'hacked',
        'id' => 9999,
    ])->assertOk()->assertHasNoErrors()->assertSee('"title":"New"');

    $talk->refresh();

    expect($talk->title)->toBe('New')
        ->and($talk->description)->toBe('Keep')
        ->and($talk->slug)->not->toBe('hacked')
        ->and($talk->id)->not->toBe(9999);
});

it('denies update without the update ability', function (): void {
    $user = userWithAbilities(Ability::TalksView);
    $talk = Talk::factory()->create(['title' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateTalkTool::class, ['ulid' => $talk->ulid, 'title' => 'New'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($talk->refresh()->title)->toBe('Old');
});

it('deletes a talk with its links', function (): void {
    $user = userWithAbilities(Ability::TalksDelete);
    $talk = Talk::factory()->create();
    $talk->speakers()->attach(Speaker::factory()->create());
    $talk->events()->attach(Event::factory()->create());

    WebsiteServer::actingAs($user)->tool(DeleteTalkTool::class, ['ulid' => $talk->ulid])
        ->assertOk()
        ->assertSee(['"deleted":true', $talk->ulid]);

    expect(Talk::query()->count())->toBe(0);
    $this->assertDatabaseCount('event_talk', 0);
    $this->assertDatabaseCount('talk_speaker', 0);
});

it('denies delete without the delete ability', function (): void {
    $user = userWithAbilities(Ability::TalksUpdate);
    $talk = Talk::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteTalkTool::class, ['ulid' => $talk->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Talk::query()->count())->toBe(1);
});
