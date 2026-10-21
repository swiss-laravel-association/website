<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreatePost
{
    /**
     * New posts always start unpublished. Only title, content and excerpt are read from `$attributes`.
     *
     * @param  array{title: string, content: string, excerpt: string}  $attributes
     * @param  array<int, int>  $authorIds
     *
     * @throws InvalidArgumentException When no author is given.
     */
    public function handle(array $attributes, array $authorIds): Post
    {
        if ($authorIds === []) {
            throw new InvalidArgumentException('A post needs at least one author.');
        }

        return DB::transaction(function () use ($attributes, $authorIds): Post {
            $post = Post::create([...Arr::only($attributes, ['title', 'content', 'excerpt']), 'published_at' => null]);
            $post->authors()->sync($authorIds);

            return $post;
        });
    }
}
