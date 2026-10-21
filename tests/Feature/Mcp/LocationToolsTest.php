<?php

use App\Enums\Ability;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Locations\CreateLocationTool;
use App\Mcp\Tools\Locations\DeleteLocationTool;
use App\Mcp\Tools\Locations\FindOrCreateLocationTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Locations\UpdateLocationTool;
use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Testing\TestResponse;

/**
 * @param  array<string, mixed>  $arguments
 * @param  array<string, string>  $headers
 */
function callMcpTool(string $tool, array $arguments, array $headers = []): TestResponse
{
    return test()->postJson('/mcp', [
        'jsonrpc' => '2.0',
        'id' => 1,
        'method' => 'tools/call',
        'params' => ['name' => $tool, 'arguments' => $arguments],
    ], $headers);
}

it('names the location tools in kebab case', function (): void {
    $names = collect([
        ListLocationsTool::class,
        GetLocationTool::class,
        CreateLocationTool::class,
        UpdateLocationTool::class,
        DeleteLocationTool::class,
        FindOrCreateLocationTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe([
        'list-locations',
        'get-location',
        'create-location',
        'update-location',
        'delete-location',
        'find-or-create-location',
    ]);
});

it('creates a location with the create ability', function (): void {
    $user = userWithAbilities(Ability::LocationsCreate);

    $response = WebsiteServer::actingAs($user)->tool(CreateLocationTool::class, [
        'name' => 'Kosmos',
        'zip_code' => '8004',
        'city' => 'Zürich',
        'capacity' => 80,
    ]);

    $location = Location::query()->sole();

    $response->assertOk()
        ->assertHasNoErrors()
        ->assertSee([$location->ulid, '"id":'.$location->id, '"name":"Kosmos"']);

    expect($location->city)->toBe('Zürich')
        ->and($location->capacity)->toBe(80);
});

it('denies create without the create ability', function (): void {
    $user = userWithAbilities(Ability::LocationsView);

    WebsiteServer::actingAs($user)->tool(CreateLocationTool::class, [
        'name' => 'Kosmos',
        'zip_code' => '8004',
        'city' => 'Zürich',
    ])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Location::query()->count())->toBe(0);
});

it('returns validation errors when zip code and city are missing', function (): void {
    $user = userWithAbilities(Ability::LocationsCreate);

    WebsiteServer::actingAs($user)->tool(CreateLocationTool::class, ['name' => 'Kosmos'])
        ->assertHasErrors(['zip code', 'city']);

    expect(Location::query()->count())->toBe(0);
});

it('lists locations', function (): void {
    $user = userWithAbilities(Ability::LocationsView);
    $locations = Location::factory()->count(2)->create();

    WebsiteServer::actingAs($user)->tool(ListLocationsTool::class)
        ->assertOk()
        ->assertSee([$locations[0]->ulid, $locations[1]->ulid]);
});

it('gets a location by ulid', function (): void {
    $user = userWithAbilities(Ability::LocationsView);
    $location = Location::factory()->create(['name' => 'Kosmos']);

    WebsiteServer::actingAs($user)->tool(GetLocationTool::class, ['ulid' => $location->ulid])
        ->assertOk()
        ->assertSee(['"name":"Kosmos"', $location->ulid]);
});

it('returns an error for an unknown location', function (): void {
    $user = userWithAbilities(Ability::LocationsView);

    WebsiteServer::actingAs($user)->tool(GetLocationTool::class, ['ulid' => '01JZZZZZZZZZZZZZZZZZZZZZZZ'])
        ->assertHasErrors(['Location [01JZZZZZZZZZZZZZZZZZZZZZZZ] not found.']);
});

it('updates a location', function (): void {
    $user = userWithAbilities(Ability::LocationsUpdate);
    $location = Location::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateLocationTool::class, ['ulid' => $location->ulid, 'name' => 'New'])
        ->assertOk()
        ->assertSee('"name":"New"');

    expect($location->refresh()->name)->toBe('New');
});

it('denies update without the update ability', function (): void {
    $user = userWithAbilities(Ability::LocationsView);
    $location = Location::factory()->create(['name' => 'Old']);

    WebsiteServer::actingAs($user)->tool(UpdateLocationTool::class, ['ulid' => $location->ulid, 'name' => 'New'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($location->refresh()->name)->toBe('Old');
});

it('finds or creates a location by name', function (): void {
    $user = userWithAbilities(Ability::LocationsView, Ability::LocationsCreate);

    WebsiteServer::actingAs($user)->tool(FindOrCreateLocationTool::class, ['name' => 'Kosmos', 'zip_code' => '8004', 'city' => 'Zürich'])
        ->assertOk();

    WebsiteServer::actingAs($user)->tool(FindOrCreateLocationTool::class, ['name' => 'kosmos'])
        ->assertOk()
        ->assertSee('"name":"Kosmos"');

    expect(Location::query()->count())->toBe(1);
});

it('requires the create ability to find or create a location', function (): void {
    $user = userWithAbilities(Ability::LocationsView);

    WebsiteServer::actingAs($user)->tool(FindOrCreateLocationTool::class, ['name' => 'Kosmos', 'zip_code' => '8004', 'city' => 'Zürich'])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Location::query()->count())->toBe(0);
});

it('deletes a location with the delete ability and detaches its events', function (): void {
    $user = userWithAbilities(Ability::LocationsDelete);
    $location = Location::factory()->create();
    $event = Event::factory()->create(['location_id' => $location->id]);

    WebsiteServer::actingAs($user)->tool(DeleteLocationTool::class, ['ulid' => $location->ulid])
        ->assertOk()
        ->assertHasNoErrors();

    expect(Location::query()->count())->toBe(0)
        ->and($event->refresh()->location_id)->toBeNull();
});

it('requires the delete ability to delete a location', function (): void {
    $user = userWithAbilities(Ability::LocationsView, Ability::LocationsUpdate);
    $location = Location::factory()->create();

    WebsiteServer::actingAs($user)->tool(DeleteLocationTool::class, ['ulid' => $location->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Location::query()->count())->toBe(1);
});

it('marks the delete tool as destructive', function (): void {
    expect((new DeleteLocationTool)->annotations())->toMatchArray(['destructiveHint' => true]);
});

it('rejects unauthenticated requests to the mcp endpoint', function (): void {
    callMcpTool('list-locations', [])->assertUnauthorized();
});

it('rejects unauthenticated requests that prefer an event stream with 401', function (): void {
    $this->post('/mcp', [
        'jsonrpc' => '2.0',
        'id' => 1,
        'method' => 'tools/list',
    ], ['Accept' => 'text/event-stream, application/json'])->assertUnauthorized();
});

it('rejects an invalid token', function (): void {
    callMcpTool('list-locations', [], ['Authorization' => 'Bearer 1|invalid'])->assertUnauthorized();
});

it('enforces token abilities over the http endpoint', function (): void {
    $token = User::factory()->create()->createToken('t', [Ability::LocationsView->value])->plainTextToken;
    $headers = ['Authorization' => "Bearer {$token}"];

    $this->postJson('/mcp', [
        'jsonrpc' => '2.0',
        'id' => 0,
        'method' => 'initialize',
        'params' => [
            'protocolVersion' => '2025-06-18',
            'capabilities' => (object) [],
            'clientInfo' => ['name' => 'test', 'version' => '1.0'],
        ],
    ], $headers)->assertOk();

    callMcpTool('create-location', ['name' => 'Kosmos', 'zip_code' => '8004', 'city' => 'Zürich'], $headers)
        ->assertOk()
        ->assertJsonPath('result.isError', true)
        ->assertJsonPath('result.content.0.text', 'This action is unauthorized.');

    callMcpTool('list-locations', [], $headers)
        ->assertOk()
        ->assertJsonPath('result.isError', false);

    expect(Location::query()->count())->toBe(0);
});
