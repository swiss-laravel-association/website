<?php

use App\Mcp\Servers\WebsiteServer;
use App\Models\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Testing\TestResponse;

/**
 * @param  array<string, mixed>  $params
 */
function mcpRequest(string $method, array $params = [], ?string $token = null): TestResponse
{
    return test()->postJson('/mcp', [
        'jsonrpc' => '2.0',
        'id' => 1,
        'method' => $method,
        'params' => (object) $params,
    ], $token === null ? [] : ['Authorization' => "Bearer {$token}"]);
}

it('creates a sanctum token for a user', function (): void {
    $user = User::factory()->create();

    $token = $user->createToken('t', ['events:view']);

    expect($token->accessToken->can('events:view'))->toBeTrue()
        ->and($token->accessToken->can('events:create'))->toBeFalse();
});

it('accepts a valid token on the mcp endpoint', function (): void {
    $token = User::factory()->create()->createToken('t', ['events:view']);

    mcpRequest('tools/list', token: $token->plainTextToken)->assertOk();
});

it('rejects a revoked token with 401', function (): void {
    $token = User::factory()->create()->createToken('t', ['events:view']);

    $token->accessToken->delete();

    mcpRequest('tools/list', token: $token->plainTextToken)->assertUnauthorized();
});

it('rejects an expired token with 401', function (): void {
    $token = User::factory()->create()->createToken('t', ['events:view'], now()->subMinute());

    mcpRequest('tools/list', token: $token->plainTextToken)->assertUnauthorized();
});

it('returns every registered tool from a single tools/list call', function (): void {
    $token = User::factory()->create()->createToken('t', ['events:view']);
    $registered = (fn (): array => $this->tools)->call((new ReflectionClass(WebsiteServer::class))->newInstanceWithoutConstructor());

    $response = mcpRequest('tools/list', token: $token->plainTextToken)->assertOk();

    expect($response->json('result.tools'))->toHaveCount(count($registered))
        ->and($response->json('result'))->not->toHaveKey('nextCursor');
});

it('rejects a logged-in web session without a bearer token', function (): void {
    $user = User::factory()->create();

    $this->withSession(['login_web_'.sha1(SessionGuard::class) => $user->id])
        ->postJson('/mcp', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list', 'params' => (object) []])
        ->assertUnauthorized();
});

it('throttles the mcp endpoint to 120 requests per minute per user', function (): void {
    $token = User::factory()->create()->createToken('t', ['events:view'])->plainTextToken;
    $other = User::factory()->create()->createToken('t', ['events:view'])->plainTextToken;

    foreach (range(1, 120) as $attempt) {
        mcpRequest('tools/list', token: $token)->assertOk();
    }

    mcpRequest('tools/list', token: $token)->assertTooManyRequests();

    // The test application keeps the resolved guard user between requests.
    app('auth')->forgetGuards();

    mcpRequest('tools/list', token: $other)->assertOk();
});
