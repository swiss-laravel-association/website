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

#[Name('get-post')]
#[Description('Get a single blog post by its ulid, including unpublished drafts, with its authors. Needs the posts:view ability.')]
#[IsReadOnly]
class GetPostTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $post = $this->findByUlid(Post::class, $validated['ulid']);

            // PostPolicy::view only lets a token see published posts. Editors need to read drafts too.
            $this->authorize($request, 'viewAny', Post::class);

            return $this->jsonResponse(PostResource::make($post)->resolve());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the post.')->required(),
        ];
    }
}
