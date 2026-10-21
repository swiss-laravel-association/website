<?php

use App\Enums\Ability;
use App\Enums\SponsorType;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Sponsors\CreateSponsorTool;
use App\Mcp\Tools\Sponsors\DeleteSponsorTool;
use App\Mcp\Tools\Sponsors\GetSponsorTool;
use App\Mcp\Tools\Sponsors\ListSponsorsTool;
use App\Mcp\Tools\Sponsors\UpdateSponsorTool;
use App\Models\Sponsor;

it('names the sponsor tools in kebab case', function (): void {
    $names = collect([
        ListSponsorsTool::class,
        GetSponsorTool::class,
        CreateSponsorTool::class,
        UpdateSponsorTool::class,
        DeleteSponsorTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe(['list-sponsors', 'get-sponsor', 'create-sponsor', 'update-sponsor', 'delete-sponsor']);
});

it('creates a sponsor with the create ability', function (): void {
    $user = userWithAbilities(Ability::SponsorsCreate);

    WebsiteServer::actingAs($user)->tool(CreateSponsorTool::class, [
        'name' => 'Acme',
        'website' => 'https://acme.test',
        'type' => 'location',
        'background_color' => '#ffffff',
    ])->assertOk()->assertHasNoErrors()->assertSee('"name":"Acme"');

    expect(Sponsor::query()->sole())
        ->name->toBe('Acme')
        ->type->toBe(SponsorType::Location);
});

it('denies create without the create ability', function (): void {
    $user = userWithAbilities(Ability::SponsorsView);

    WebsiteServer::actingAs($user)->tool(CreateSponsorTool::class, [
        'name' => 'Acme',
        'website' => 'https://acme.test',
        'type' => 'founding',
    ])->assertHasErrors(['This action is unauthorized.']);

    expect(Sponsor::query()->count())->toBe(0);
});

it('rejects an unknown sponsor type on create', function (): void {
    $user = userWithAbilities(Ability::SponsorsCreate);

    WebsiteServer::actingAs($user)->tool(CreateSponsorTool::class, [
        'name' => 'Acme',
        'website' => 'https://acme.test',
        'type' => 'gold',
    ])->assertHasErrors(['type']);

    expect(Sponsor::query()->count())->toBe(0);
});

it('rejects an unknown sponsor type on update', function (): void {
    $user = userWithAbilities(Ability::SponsorsUpdate);
    $sponsor = Sponsor::factory()->isFoundingSponsor()->create();

    WebsiteServer::actingAs($user)->tool(UpdateSponsorTool::class, ['ulid' => $sponsor->ulid, 'type' => 'gold'])
        ->assertHasErrors(['type']);

    expect($sponsor->refresh()->type)->toBe(SponsorType::Founding);
});

it('lists all sponsors', function (): void {
    $user = userWithAbilities(Ability::SponsorsView);
    $sponsors = Sponsor::factory()->count(3)->create();

    WebsiteServer::actingAs($user)->tool(ListSponsorsTool::class)
        ->assertOk()
        ->assertSee($sponsors->pluck('ulid')->all());
});

it('denies list without the view ability', function (): void {
    $user = userWithAbilities(Ability::SponsorsCreate);

    WebsiteServer::actingAs($user)->tool(ListSponsorsTool::class)
        ->assertHasErrors(['This action is unauthorized.']);
});

it('gets a sponsor by ulid', function (): void {
    $user = userWithAbilities(Ability::SponsorsView);
    $sponsor = Sponsor::factory()->create(['name' => 'Acme']);

    WebsiteServer::actingAs($user)->tool(GetSponsorTool::class, ['ulid' => $sponsor->ulid])
        ->assertOk()
        ->assertSee(['"name":"Acme"', $sponsor->ulid]);
});

it('returns an error for an unknown sponsor', function (): void {
    $user = userWithAbilities(Ability::SponsorsView, Ability::SponsorsUpdate, Ability::SponsorsDelete);
    $ulid = '01JZZZZZZZZZZZZZZZZZZZZZZZ';

    foreach ([GetSponsorTool::class, UpdateSponsorTool::class, DeleteSponsorTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, ['ulid' => $ulid])
            ->assertHasErrors(["Sponsor [{$ulid}] not found."]);
    }
});

it('requires a ulid on get, update and delete', function (): void {
    $user = userWithAbilities(Ability::SponsorsView, Ability::SponsorsUpdate, Ability::SponsorsDelete);

    foreach ([GetSponsorTool::class, UpdateSponsorTool::class, DeleteSponsorTool::class] as $tool) {
        WebsiteServer::actingAs($user)->tool($tool, [])->assertHasErrors(['ulid']);
    }
});

it('denies get without the view ability', function (): void {
    $user = userWithAbilities(Ability::SponsorsCreate);
    $sponsor = Sponsor::factory()->create();

    WebsiteServer::actingAs($user)->tool(GetSponsorTool::class, ['ulid' => $sponsor->ulid])
        ->assertHasErrors(['This action is unauthorized.']);
});

it('updates a sponsor', function (): void {
    $user = userWithAbilities(Ability::SponsorsUpdate);
    $sponsor = Sponsor::factory()->isFoundingSponsor()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateSponsorTool::class, [
        'ulid' => $sponsor->ulid,
        'name' => 'New',
        'type' => 'location',
    ])->assertOk()->assertSee('"name":"New"');

    $sponsor->refresh();

    expect($sponsor->name)->toBe('New')
        ->and($sponsor->type)->toBe(SponsorType::Location);
});

it('ignores keys without validation rules on update', function (): void {
    $user = userWithAbilities(Ability::SponsorsUpdate);
    $sponsor = Sponsor::factory()->create(['name' => 'Old', 'order' => 0]);

    WebsiteServer::actingAs($user)->tool(UpdateSponsorTool::class, [
        'ulid' => $sponsor->ulid,
        'name' => 'New',
        'order' => 99,
    ])->assertOk();

    expect($sponsor->refresh()->order)->toBe(0);
});

it('denies update without the update ability', function (): void {
    $user = userWithAbilities(Ability::SponsorsView);
    $sponsor = Sponsor::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateSponsorTool::class, ['ulid' => $sponsor->ulid, 'name' => 'New'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($sponsor->refresh()->name)->toBe('Old');
});

it('deletes a sponsor', function (): void {
    $user = userWithAbilities(Ability::SponsorsDelete);
    $sponsor = Sponsor::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteSponsorTool::class, ['ulid' => $sponsor->ulid])
        ->assertOk()
        ->assertSee(['"deleted":true', $sponsor->ulid]);

    expect(Sponsor::query()->count())->toBe(0);
});

it('denies delete without the delete ability', function (): void {
    $user = userWithAbilities(Ability::SponsorsView);
    $sponsor = Sponsor::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteSponsorTool::class, ['ulid' => $sponsor->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Sponsor::query()->count())->toBe(1);
});
