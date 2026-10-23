<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Sponsor;
use App\Models\User;
use App\Policies\Concerns\ChecksTokenAbility;

class SponsorPolicy
{
    use ChecksTokenAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Ability::SponsorsView);
    }

    public function view(User $user, Sponsor $sponsor): bool
    {
        return $this->allows($user, Ability::SponsorsView);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Ability::SponsorsCreate);
    }

    public function update(User $user, Sponsor $sponsor): bool
    {
        return $this->allows($user, Ability::SponsorsUpdate);
    }

    public function delete(User $user, Sponsor $sponsor): bool
    {
        return $this->allows($user, Ability::SponsorsDelete);
    }
}
