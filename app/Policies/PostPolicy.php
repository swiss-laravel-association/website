<?php

namespace App\Policies;

use App\Enums\Ability;
use App\Models\Post;
use App\Models\User;
use App\Policies\Concerns\ChecksTokenAbility;

class PostPolicy
{
    use ChecksTokenAbility;

    public function viewAny(?User $user): bool
    {
        if (! $user instanceof User) {
            return true;
        }

        return $this->allows($user, Ability::PostsView);
    }

    public function view(?User $user, Post $post): bool
    {
        if (! $user instanceof User) {
            return $post->published_at?->isPast() ?? false;
        }

        if (! $this->allows($user, Ability::PostsView)) {
            return false;
        }

        return $post->published_at?->isPast() ?? false;
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Ability::PostsCreate);
    }

    public function update(User $user, Post $post): bool
    {
        return $this->allows($user, Ability::PostsUpdate);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->allows($user, Ability::PostsDelete);
    }

    public function publish(User $user, Post $post): bool
    {
        return $this->allows($user, Ability::PostsPublish);
    }

    public function restore(User $user, Post $post): bool
    {
        return $this->allows($user, Ability::PostsDelete);
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $this->allows($user, Ability::PostsDelete);
    }
}
