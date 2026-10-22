<?php

use App\Enums\Ability;
use App\Filament\Pages\ApiTokens;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('renders the page for a verified user', function (): void {
    get('/admin/api-tokens')->assertOk()->assertSee(url('/mcp'));
});

it('lists only the tokens of the current user', function (): void {
    $own = $this->user->createToken('own token', ['events:view'])->accessToken;
    $foreign = User::factory()->create()->createToken('foreign token', ['events:view'])->accessToken;

    Livewire::test(ApiTokens::class)
        ->assertCanSeeTableRecords([$own])
        ->assertCanNotSeeTableRecords([$foreign]);
});

it('creates a token with the selected abilities and exposes the plain-text value once', function (): void {
    $component = Livewire::test(ApiTokens::class)
        ->callAction('createToken', [
            'name' => 'Claude Desktop',
            'abilities_events' => [Ability::EventsView->value, Ability::EventsCreate->value],
            'abilities_posts' => [],
        ])
        ->assertHasNoActionErrors();

    $token = $this->user->tokens()->firstOrFail();
    expect($token->name)->toBe('Claude Desktop')
        ->and($token->abilities)->toEqualCanonicalizing(['events:view', 'events:create'])
        ->and($token->expires_at)->toBeNull();

    $plainText = $component->get('newToken');
    expect($plainText)->toStartWith($token->getKey().'|');

    $component->assertSeeHtml('token: '.Js::from($plainText))
        ->assertDontSeeHtml('@js(');

    $component->call('dismissNewToken')->assertSet('newToken', null);
});

it('requires at least one ability', function (): void {
    Livewire::test(ApiTokens::class)
        ->callAction('createToken', ['name' => 'No abilities'])
        ->assertHasActionErrors(['abilities_events' => 'required']);

    expect($this->user->tokens()->count())->toBe(0);
});

it('requires a name and a future expiry date', function (): void {
    Livewire::test(ApiTokens::class)
        ->callAction('createToken', [
            'name' => '',
            'abilities_events' => ['events:view'],
            'expires_at' => now()->subDay()->toDateString(),
        ])
        ->assertHasActionErrors(['name' => 'required', 'expires_at']);
});

it('stores the expiry date', function (): void {
    Livewire::test(ApiTokens::class)
        ->callAction('createToken', [
            'name' => 'Expiring',
            'abilities_events' => ['events:view'],
            'expires_at' => now()->addDays(10)->toDateString(),
        ])
        ->assertHasNoActionErrors();

    expect($this->user->tokens()->first()->expires_at->isFuture())->toBeTrue();
});

it('revokes an own token', function (): void {
    $token = $this->user->createToken('own token', ['events:view'])->accessToken;

    Livewire::test(ApiTokens::class)
        ->callAction(TestAction::make('revoke')->table($token))
        ->assertHasNoActionErrors();

    assertDatabaseMissing('personal_access_tokens', ['id' => $token->getKey()]);
});

it('cannot revoke the token of another user', function (): void {
    $foreign = User::factory()->create()->createToken('foreign token', ['events:view'])->accessToken;

    try {
        Livewire::test(ApiTokens::class)
            ->callAction(TestAction::make('revoke')->table($foreign));
    } catch (Throwable) {
        // The record is not part of the scoped table query.
    }

    assertDatabaseHas('personal_access_tokens', ['id' => $foreign->getKey()]);
    expect(PersonalAccessToken::count())->toBe(1);
});

it('rejects a forged ability value', function (): void {
    Livewire::test(ApiTokens::class)
        ->callAction('createToken', [
            'name' => 'Forged',
            'abilities_events' => ['*'],
        ])
        ->assertHasActionErrors(['abilities_events.0']);

    expect($this->user->tokens()->count())->toBe(0);
});

it('creates no token when name or expiry validation fails', function (): void {
    Livewire::test(ApiTokens::class)
        ->callAction('createToken', [
            'name' => '',
            'abilities_events' => ['events:view'],
            'expires_at' => now()->subDay()->toDateString(),
        ])
        ->assertHasActionErrors(['name', 'expires_at']);

    expect($this->user->tokens()->count())->toBe(0);
});

it('redirects guests away from the page', function (): void {
    auth()->logout();

    get('/admin/api-tokens')->assertRedirect();
});
