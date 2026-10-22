<?php

use App\Models\Event;
use App\Models\Location;
use App\Models\Post;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\Talk;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

/**
 * @return array<string, array{class-string, string, list<string>}>
 */
function policyResources(): array
{
    return [
        'events' => [Event::class, 'events', ['viewAny', 'view', 'create', 'update', 'delete', 'publish']],
        'talks' => [Talk::class, 'talks', ['viewAny', 'view', 'create', 'update', 'delete']],
        'speakers' => [Speaker::class, 'speakers', ['viewAny', 'view', 'create', 'update', 'delete']],
        'locations' => [Location::class, 'locations', ['viewAny', 'view', 'create', 'update', 'delete']],
        'sponsors' => [Sponsor::class, 'sponsors', ['viewAny', 'view', 'create', 'update', 'delete']],
        'posts' => [Post::class, 'posts', ['viewAny', 'view', 'create', 'update', 'delete', 'publish']],
    ];
}

/**
 * @param  class-string  $model
 */
function policyArgument(string $model, string $method): mixed
{
    return in_array($method, ['viewAny', 'create'], true) ? $model : $model::factory()->create();
}

dataset('policy resources', policyResources());

it('lets session users do everything', function (string $model, string $prefix, array $methods): void {
    $user = User::factory()->create();

    foreach ($methods as $method) {
        expect(Gate::forUser($user)->allows($method, policyArgument($model, $method)))->toBeTrue("$method failed");
    }
})->with('policy resources');

it('lets tokens with a wildcard do everything', function (string $model, string $prefix, array $methods): void {
    Sanctum::actingAs(User::factory()->create(), ['*']);

    foreach ($methods as $method) {
        expect(Gate::allows($method, policyArgument($model, $method)))->toBeTrue("$method failed");
    }
})->with('policy resources');

it('only allows what the token ability grants', function (string $model, string $prefix, array $methods): void {
    Sanctum::actingAs(User::factory()->create(), ["{$prefix}:update"]);

    expect(Gate::allows('update', policyArgument($model, 'update')))->toBeTrue()
        ->and(Gate::allows('delete', policyArgument($model, 'delete')))->toBeFalse()
        ->and(Gate::allows('create', $model))->toBeFalse();

    if (in_array('publish', $methods, true)) {
        expect(Gate::allows('publish', policyArgument($model, 'publish')))->toBeFalse();
    }
})->with('policy resources');

it('maps view and publish abilities', function (): void {
    Sanctum::actingAs(User::factory()->create(), ['events:view', 'events:publish']);

    expect(Gate::allows('view', Event::factory()->create()))->toBeTrue()
        ->and(Gate::allows('viewAny', Event::class))->toBeTrue()
        ->and(Gate::allows('publish', Event::factory()->create()))->toBeTrue();
});

it('does not throw for guests on unpublished posts', function (): void {
    $post = Post::factory()->create(['published_at' => null]);

    expect(Gate::forUser(null)->allows('view', $post))->toBeFalse();
});

it('lets guests view published posts', function (): void {
    $post = Post::factory()->create(['published_at' => now()->subDay()]);

    expect(Gate::forUser(null)->allows('view', $post))->toBeTrue()
        ->and(Gate::forUser(null)->allows('viewAny', Post::class))->toBeTrue();
});

it('requires posts:view for token users to view posts', function (): void {
    $post = Post::factory()->create(['published_at' => now()->subDay()]);

    Sanctum::actingAs(User::factory()->create(), ['posts:update']);
    expect(Gate::allows('view', $post))->toBeFalse()
        ->and(Gate::allows('viewAny', Post::class))->toBeFalse();

    Sanctum::actingAs(User::factory()->create(), ['posts:view']);
    expect(Gate::allows('view', $post))->toBeTrue()
        ->and(Gate::allows('viewAny', Post::class))->toBeTrue();
});
