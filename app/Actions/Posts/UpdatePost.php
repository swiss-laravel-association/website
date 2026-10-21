<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class UpdatePost
{
    /**
     * Only title, content and excerpt are read from `$attributes`; publishing goes through
     * PublishPost and UnpublishPost. Authors are left untouched when `$authorIds` is null.
     *
     * @param  array{title?: string, content?: string, excerpt?: string}  $attributes
     * @param  array<int, int>|null  $authorIds
     *
     * @throws InvalidArgumentException When an empty author list is given.
     */
    public function handle(Post $post, array $attributes, ?array $authorIds = null): Post
    {
        if ($authorIds === []) {
            throw new InvalidArgumentException('A post needs at least one author.');
        }

        return DB::transaction(function () use ($post, $attributes, $authorIds): Post {
            $post->update(Arr::only($attributes, ['title', 'content', 'excerpt']));

            if ($authorIds !== null) {
                $post->authors()->sync($authorIds);
            }

            return $post;
        });
    }
}
