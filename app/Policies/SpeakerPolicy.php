<?php

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Speaker;
use App\Models\User;
use App\Policies\Concerns\ChecksTokenAbility;

class SpeakerPolicy
{
    use ChecksTokenAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Ability::SpeakersView);
    }

    public function view(User $user, Speaker $speaker): bool
    {
        return $this->allows($user, Ability::SpeakersView);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Ability::SpeakersCreate);
    }

    public function update(User $user, Speaker $speaker): bool
    {
        return $this->allows($user, Ability::SpeakersUpdate);
    }

    public function delete(User $user, Speaker $speaker): bool
    {
        return $this->allows($user, Ability::SpeakersDelete);
    }
}
