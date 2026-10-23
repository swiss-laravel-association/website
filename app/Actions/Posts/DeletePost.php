<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class DeletePost
{
    /**
     * The `post_user` foreign keys do not cascade. `Post` detaches its authors in a
     * `deleting` hook; the transaction makes that detach and the delete atomic.
     */
    public function handle(Post $post): void
    {
        DB::transaction(function () use ($post): void {
            $post->authors()->detach();
            $post->delete();
        });
    }
}
