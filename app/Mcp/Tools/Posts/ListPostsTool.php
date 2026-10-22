<?php

namespace App\Mcp\Tools\Posts;

use App\Http\Resources\PostResource;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Post;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list-posts')]
#[Description('List all blog posts, newest first, including unpublished drafts. Each post includes its id, ulid, title, slug, excerpt, content, published_at (null for drafts), is_published, public url and authors (integer user id and name).')]
#[IsReadOnly]
class ListPostsTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $this->authorize($request, 'viewAny', Post::class);

            $posts = Post::query()->with('authors')->latest('id')->get();

            return $this->jsonResponse(PostResource::collection($posts)->resolve());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
