<?php

use App\Models\User;

it('creates a sanctum token for a user', function (): void {
    $user = User::factory()->create();

    $token = $user->createToken('t', ['events:view']);

    expect($token->accessToken->can('events:view'))->toBeTrue()
        ->and($token->accessToken->can('events:create'))->toBeFalse();
});
