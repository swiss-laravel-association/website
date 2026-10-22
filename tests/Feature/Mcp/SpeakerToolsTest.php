<?php

use App\Enums\Ability;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Speakers\CreateSpeakerTool;
use App\Mcp\Tools\Speakers\DeleteSpeakerTool;
use App\Mcp\Tools\Speakers\GetSpeakerTool;
use App\Mcp\Tools\Speakers\ListSpeakersTool;
use App\Mcp\Tools\Speakers\UpdateSpeakerTool;
use App\Models\Speaker;

it('names the speaker tools in kebab case', function (): void {
    $names = collect([
        ListSpeakersTool::class,
        GetSpeakerTool::class,
        CreateSpeakerTool::class,
        UpdateSpeakerTool::class,
        DeleteSpeakerTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe(['list-speakers', 'get-speaker', 'create-speaker', 'update-speaker', 'delete-speaker']);
});

it('creates a speaker with the create ability', function (): void {
    $user = userWithAbilities(Ability::SpeakersCreate);

    WebsiteServer::actingAs($user)->tool(CreateSpeakerTool::class, [
        'name' => 'Jane Doe',
        'bio' => 'Hello',
        'github_profile' => 'https://github.com/jane',
    ])->assertOk()->assertHasNoErrors()->assertSee('"name":"Jane Doe"');

    expect(Speaker::query()->sole())
        ->name->toBe('Jane Doe')
        ->github_profile->toBe('https://github.com/jane');
});

it('denies create without the create ability', function (): void {
    $user = userWithAbilities(Ability::SpeakersView);

    WebsiteServer::actingAs($user)->tool(CreateSpeakerTool::class, ['name' => 'Jane Doe'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Speaker::query()->count())->toBe(0);
});

it('requires a speaker name', function (): void {
    $user = userWithAbilities(Ability::SpeakersCreate);

    WebsiteServer::actingAs($user)->tool(CreateSpeakerTool::class, ['bio' => 'x'])
        ->assertHasErrors(['name']);

    expect(Speaker::query()->count())->toBe(0);
});

it('lists all speakers', function (): void {
    $user = userWithAbilities(Ability::SpeakersView);
    $speakers = Speaker::factory()->count(3)->create();

    WebsiteServer::actingAs($user)->tool(ListSpeakersTool::class)
        ->assertOk()
        ->assertSee($speakers->pluck('ulid')->all());
});

it('denies list without the view ability', function (): void {
    $user = userWithAbilities(Ability::SpeakersCreate);

    WebsiteServer::actingAs($user)->tool(ListSpeakersTool::class)
        ->assertHasErrors(['This action is unauthorized.']);
});

it('gets a speaker by ulid', function (): void {
    $user = userWithAbilities(Ability::SpeakersView);
    $speaker = Speaker::factory()->create(['name' => 'Jane Doe']);

    WebsiteServer::actingAs($user)->tool(GetSpeakerTool::class, ['ulid' => $speaker->ulid])
        ->assertOk()
        ->assertSee(['"name":"Jane Doe"', $speaker->ulid]);
});

it('returns an error for an unknown speaker', function (): void {
    $user = userWithAbilities(Ability::SpeakersView, Ability::SpeakersUpdate, Ability::SpeakersDelete);
    $ulid = '01JZZZZZZZZZZZZZZZZZZZZZZZ';

    foreach ([GetSpeakerTool::class, UpdateSpeakerTool::class, DeleteSpeakerTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, ['ulid' => $ulid])
            ->assertHasErrors(["Speaker [{$ulid}] not found."]);
    }
});

it('requires a ulid on get, update and delete', function (): void {
    $user = userWithAbilities(Ability::SpeakersView, Ability::SpeakersUpdate, Ability::SpeakersDelete);

    foreach ([GetSpeakerTool::class, UpdateSpeakerTool::class, DeleteSpeakerTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, [])->assertHasErrors(['ulid']);
    }
});

it('denies get without the view ability', function (): void {
    $user = userWithAbilities(Ability::SpeakersCreate);
    $speaker = Speaker::factory()->create();

    WebsiteServer::actingAs($user)->tool(GetSpeakerTool::class, ['ulid' => $speaker->ulid])
        ->assertHasErrors(['This action is unauthorized.']);
});

it('updates a speaker', function (): void {
    $user = userWithAbilities(Ability::SpeakersUpdate);
    $speaker = Speaker::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateSpeakerTool::class, ['ulid' => $speaker->ulid, 'name' => 'New'])
        ->assertOk()
        ->assertSee('"name":"New"');

    expect($speaker->refresh()->name)->toBe('New');
});

it('ignores keys without validation rules on update', function (): void {
    $user = userWithAbilities(Ability::SpeakersUpdate);
    $speaker = Speaker::factory()->create(['name' => 'Old', 'slug' => 'old']);

    WebsiteServer::actingAs($user)->tool(UpdateSpeakerTool::class, [
        'ulid' => $speaker->ulid,
        'name' => 'New',
        'slug' => 'hacked',
        'id' => 9999,
    ])->assertOk();

    $speaker->refresh();

    expect($speaker->name)->toBe('New')
        ->and($speaker->slug)->not->toBe('hacked')
        ->and($speaker->id)->not->toBe(9999);
});

it('denies update without the update ability', function (): void {
    $user = userWithAbilities(Ability::SpeakersView);
    $speaker = Speaker::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateSpeakerTool::class, ['ulid' => $speaker->ulid, 'name' => 'New'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($speaker->refresh()->name)->toBe('Old');
});

it('deletes a speaker', function (): void {
    $user = userWithAbilities(Ability::SpeakersDelete);
    $speaker = Speaker::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteSpeakerTool::class, ['ulid' => $speaker->ulid])
        ->assertOk()
        ->assertSee(['"deleted":true', $speaker->ulid]);

    expect(Speaker::query()->count())->toBe(0);
});

it('denies delete without the delete ability', function (): void {
    $user = userWithAbilities(Ability::SpeakersView);
    $speaker = Speaker::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteSpeakerTool::class, ['ulid' => $speaker->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Speaker::query()->count())->toBe(1);
});
