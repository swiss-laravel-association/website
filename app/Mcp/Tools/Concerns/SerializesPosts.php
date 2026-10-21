<?php

namespace App\Mcp\Tools\Concerns;

use App\Models\Post;
use App\Models\User;

/**
 * Turns posts into arrays for tool responses: the post attributes plus `is_published`, the
 * public `url` and the authors (integer `id` and `name`).
 */
trait SerializesPosts
{
    /**
     * @return array<string, mixed>
     */
    protected function postPayload(Post $post): array
    {
        $post->loadMissing('authors');

        return [
            ...$post->attributesToArray(),
            'is_published' => $post->published_at !== null && $post->published_at->isPast(),
            'url' => route('blog.show', $post->slug),
            'authors' => $post->authors->map(fn (User $author): array => [
                'id' => $author->id,
                'name' => $author->name,
            ])->all(),
        ];
    }
}
