<?php

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Event;
use App\Models\User;
use App\Policies\Concerns\ChecksTokenAbility;

class EventPolicy
{
    use ChecksTokenAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Ability::EventsView);
    }

    public function view(User $user, Event $event): bool
    {
        return $this->allows($user, Ability::EventsView);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Ability::EventsCreate);
    }

    public function update(User $user, Event $event): bool
    {
        return $this->allows($user, Ability::EventsUpdate);
    }

    public function delete(User $user, Event $event): bool
    {
        return $this->allows($user, Ability::EventsDelete);
    }

    public function publish(User $user, Event $event): bool
    {
        return $this->allows($user, Ability::EventsPublish);
    }
}
