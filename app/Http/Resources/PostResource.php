<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Post attributes plus `is_published`, the public `url` and the authors (integer `id` and
 * `name`). Authors are always included.
 *
 * @mixin Post
 */
class PostResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing('authors');

        return [
            ...$this->resource->attributesToArray(),
            'is_published' => $this->published_at !== null && $this->published_at->isPast(),
            'url' => route('blog.show', $this->slug),
            'authors' => $this->authors->map(fn (User $author): array => [
                'id' => $author->id,
                'name' => $author->name,
            ])->all(),
        ];
    }
}
