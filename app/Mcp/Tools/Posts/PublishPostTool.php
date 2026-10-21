<?php

namespace App\Mcp\Tools\Posts;

use App\Actions\Posts\PublishPost;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Mcp\Tools\Concerns\SerializesPosts;
use App\Models\Post;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('publish-post')]
#[Description('Publish a blog post by its ulid so it appears on the website. An already published post keeps its original publication date. Needs the posts:publish ability.')]
#[IsIdempotent]
class PublishPostTool extends Tool
{
    use AuthorizesToolCalls;
    use SerializesPosts;

    public function handle(Request $request, PublishPost $action): Response
    {
        return $this->respond(function () use ($request, $action): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $post = $this->findByUlid(Post::class, $validated['ulid']);

            $this->authorize($request, 'publish', $post);

            return $this->jsonResponse($this->postPayload($action->handle($post)));
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
