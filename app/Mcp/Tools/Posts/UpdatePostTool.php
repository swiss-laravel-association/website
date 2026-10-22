<?php

namespace App\Mcp\Tools\Posts;

use App\Actions\Posts\UpdatePost;
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
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('update-post')]
#[Description('Update a blog post by its ulid. Only the fields you pass are changed; use publish-post and unpublish-post to change visibility. author_ids are integer user ids and replace the current authors; omit it to keep them.')]
#[IsIdempotent]
class UpdatePostTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, UpdatePost $updatePost): Response
    {
        return $this->respond(function () use ($request, $updatePost): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $post = $this->findByUlid(Post::class, $validated['ulid']);

            $this->authorize($request, 'update', $post);

            /** @var array{title?: string, content?: string, excerpt?: string, author_ids?: array<int, int>} $input */
            $input = $request->validate([
                'title' => ['sometimes', 'required', 'string', 'max:255'],
                'content' => ['sometimes', 'required', 'string'],
                'excerpt' => ['sometimes', 'required', 'string'],
                'author_ids' => ['sometimes', 'array', 'min:1'],
                'author_ids.*' => ['integer', 'distinct', 'exists:users,id'],
            ]);

            $authorIds = isset($input['author_ids']) ? array_values($input['author_ids']) : null;
            unset($input['author_ids']);

            $post = $updatePost->handle($post, $input, $authorIds);

            return $this->jsonResponse(PostResource::make($post->unsetRelation('authors'))->resolve());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the post to update.')->required(),
            'title' => $schema->string()->description('Title of the post.'),
            'content' => $schema->string()->description('Body of the post as Markdown.'),
            'excerpt' => $schema->string()->description('Short summary shown in lists and search results.'),
            'author_ids' => $schema->array()->items($schema->integer())->description('Integer ids of the authors (users have no ulid). Replaces the current authors.'),
        ];
    }
}
