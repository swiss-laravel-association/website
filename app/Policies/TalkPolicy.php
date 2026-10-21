<?php

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Talk;
use App\Models\User;
use App\Policies\Concerns\ChecksTokenAbility;

class TalkPolicy
{
    use ChecksTokenAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Ability::TalksView);
    }

    public function view(User $user, Talk $talk): bool
    {
        return $this->allows($user, Ability::TalksView);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Ability::TalksCreate);
    }

    public function update(User $user, Talk $talk): bool
    {
        return $this->allows($user, Ability::TalksUpdate);
    }

    public function delete(User $user, Talk $talk): bool
    {
        return $this->allows($user, Ability::TalksDelete);
    }
}
