<?php

namespace App\Actions\Posts;

use App\Models\Post;

class PublishPost
{
    /**
     * A post without `published_at` or scheduled for the future is published now.
     * A post that is already published keeps its original `published_at`.
     */
    public function handle(Post $post): Post
    {
        if ($post->published_at === null || $post->published_at->isFuture()) {
            $post->update(['published_at' => now()]);
        }

        return $post;
    }
}
