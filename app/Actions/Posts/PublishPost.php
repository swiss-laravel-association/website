<?php

namespace App\Actions\Posts;

use App\Models\Post;

class PublishPost
{
    /**
     * An already published post keeps its original `published_at`.
     */
    public function handle(Post $post): Post
    {
        if ($post->published_at === null) {
            $post->update(['published_at' => now()]);
        }

        return $post;
    }
}
