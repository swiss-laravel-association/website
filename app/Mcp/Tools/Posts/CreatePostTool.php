<?php

namespace App\Mcp\Tools\Posts;

use App\Actions\Posts\CreatePost;
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

#[Name('create-post')]
#[Description('Create a blog post. The post is always created unpublished; use publish-post to publish it. content is Markdown. author_ids are integer user ids (users have no ulid); when omitted, the user of the API token becomes the only author.')]
class CreatePostTool extends Tool
{
    use AuthorizesToolCalls;
    use SerializesPosts;

    public function handle(Request $request, CreatePost $createPost): Response
    {
        return $this->respond(function () use ($request, $createPost): Response {
            $this->authorize($request, 'create', Post::class);

            /** @var array{title: string, content: string, excerpt: string, author_ids?: array<int, int>} $validated */
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'excerpt' => ['required', 'string'],
                'author_ids' => ['sometimes', 'array', 'min:1'],
                'author_ids.*' => ['integer', 'distinct', 'exists:users,id'],
            ]);

            $authorIds = $validated['author_ids'] ?? [(int) $request->user()?->getAuthIdentifier()];

            $post = $createPost->handle([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
            ], array_values($authorIds));

            return $this->jsonResponse($this->postPayload($post->refresh()));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('Title of the post. The slug is generated from it.')->required(),
            'content' => $schema->string()->description('Body of the post as Markdown.')->required(),
            'excerpt' => $schema->string()->description('Short summary shown in lists and search results.')->required(),
            'author_ids' => $schema->array()->items($schema->integer())->description('Integer ids of the authors (users have no ulid). Defaults to the user of the API token.'),
        ];
    }
}
