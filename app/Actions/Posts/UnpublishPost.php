<?php

namespace App\Actions\Posts;

use App\Models\Post;

class UnpublishPost
{
    public function handle(Post $post): Post
    {
        $post->update(['published_at' => null]);

        return $post;
    }
}
