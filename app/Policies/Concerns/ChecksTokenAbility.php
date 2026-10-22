<?php

namespace App\Policies\Concerns;

use App\Enums\Ability;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

trait ChecksTokenAbility
{
    /**
     * Session users (no API token) are always allowed. Token users need the given ability.
     */
    protected function allows(User $user, Ability $ability): bool
    {
        if (! $user->currentAccessToken() instanceof PersonalAccessToken) {
            return true;
        }

        return $user->tokenCan($ability->value);
    }
}
