<?php

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Location;
use App\Models\User;
use App\Policies\Concerns\ChecksTokenAbility;

class LocationPolicy
{
    use ChecksTokenAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Ability::LocationsView);
    }

    public function view(User $user, Location $location): bool
    {
        return $this->allows($user, Ability::LocationsView);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Ability::LocationsCreate);
    }

    public function update(User $user, Location $location): bool
    {
        return $this->allows($user, Ability::LocationsUpdate);
    }

    public function delete(User $user, Location $location): bool
    {
        return $this->allows($user, Ability::LocationsDelete);
    }
}
