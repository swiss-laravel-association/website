<?php

use App\Enums\Ability;
use App\Mcp\Servers\WebsiteServer;
use App\Mcp\Tools\Posts\CreatePostTool;
use App\Mcp\Tools\Posts\DeletePostTool;
use App\Mcp\Tools\Posts\GetPostTool;
use App\Mcp\Tools\Posts\ListPostsTool;
use App\Mcp\Tools\Posts\PublishPostTool;
use App\Mcp\Tools\Posts\UnpublishPostTool;
use App\Mcp\Tools\Posts\UpdatePostTool;
use App\Models\Post;
use App\Models\User;

/**
 * @return array<string, string>
 */
function postInput(array $overrides = []): array
{
    return [
        'title' => 'Hello Laravel',
        'content' => 'Some **markdown** content.',
        'excerpt' => 'A short excerpt.',
        ...$overrides,
    ];
}

function draftPost(): Post
{
    $post = Post::factory()->create(['published_at' => null]);
    $post->authors()->attach(User::factory()->create());

    return $post;
}

it('names the post tools in kebab case', function (): void {
    $names = collect([
        ListPostsTool::class,
        GetPostTool::class,
        CreatePostTool::class,
        UpdatePostTool::class,
        DeletePostTool::class,
        PublishPostTool::class,
        UnpublishPostTool::class,
    ])->map(fn (string $tool): string => (new $tool)->name())->all();

    expect($names)->toBe([
        'list-posts',
        'get-post',
        'create-post',
        'update-post',
        'delete-post',
        'publish-post',
        'unpublish-post',
    ]);
});

it('marks delete-post as destructive', function (): void {
    expect((new DeletePostTool)->annotations())->toMatchArray(['destructiveHint' => true]);
});

it('creates an unpublished post with the token user as author when author_ids is omitted', function (): void {
    $user = userWithAbilities(Ability::PostsCreate);

    $response = WebsiteServer::actingAs($user)->tool(CreatePostTool::class, postInput(['published_at' => now()->toIso8601String()]))
        ->assertOk();

    $data = toolJson($response);
    $post = Post::query()->where('ulid', $data['ulid'])->firstOrFail();

    expect($post->published_at)->toBeNull()
        ->and($data['is_published'])->toBeFalse()
        ->and($data['authors'])->toHaveCount(1)
        ->and($data['authors'][0]['id'])->toBe($user->id)
        ->and($post->authors->modelKeys())->toBe([$user->id]);
});

it('creates a post with the given integer author ids', function (): void {
    $authors = User::factory()->count(2)->create();

    $response = WebsiteServer::actingAs(userWithAbilities(Ability::PostsCreate))
        ->tool(CreatePostTool::class, postInput(['author_ids' => $authors->modelKeys()]))
        ->assertOk();

    $post = Post::query()->where('ulid', toolJson($response)['ulid'])->firstOrFail();

    expect($post->authors->modelKeys())->toEqualCanonicalizing($authors->modelKeys());
});

it('rejects unknown author ids and missing fields on create', function (): void {
    $user = userWithAbilities(Ability::PostsCreate);

    WebsiteServer::actingAs($user)->tool(CreatePostTool::class, postInput(['author_ids' => [999999]]))->assertHasErrors();
    WebsiteServer::actingAs($user)->tool(CreatePostTool::class, postInput(['author_ids' => []]))->assertHasErrors();
    WebsiteServer::actingAs($user)->tool(CreatePostTool::class, ['title' => 'Only title'])->assertHasErrors();

    expect(Post::query()->count())->toBe(0);
});

it('denies create-post without posts:create', function (): void {
    WebsiteServer::actingAs(userWithAbilities(Ability::PostsView))
        ->tool(CreatePostTool::class, postInput())
        ->assertHasErrors(['This action is unauthorized.']);
});

it('lists posts including drafts with posts:view', function (): void {
    $draft = draftPost();
    $published = Post::factory()->create();
    $published->authors()->attach(User::factory()->create());

    $response = WebsiteServer::actingAs(userWithAbilities(Ability::PostsView))->tool(ListPostsTool::class, [])->assertOk();

    expect(collect(toolJson($response))->pluck('ulid')->all())->toContain($draft->ulid, $published->ulid);
});

it('denies list-post without posts:view', function (): void {
    WebsiteServer::actingAs(userWithAbilities(Ability::EventsView))
        ->tool(ListPostsTool::class, [])
        ->assertHasErrors(['This action is unauthorized.']);
});

it('gets a draft post with authors and url', function (): void {
    $post = draftPost();

    $data = toolJson(WebsiteServer::actingAs(userWithAbilities(Ability::PostsView))
        ->tool(GetPostTool::class, ['ulid' => $post->ulid])
        ->assertOk());

    expect($data['ulid'])->toBe($post->ulid)
        ->and($data['is_published'])->toBeFalse()
        ->and($data['url'])->toBe(route('blog.show', $post->slug))
        ->and($data['authors'])->toHaveCount(1);
});

it('validates and finds the ulid on get-post', function (): void {
    $user = userWithAbilities(Ability::PostsView);

    WebsiteServer::actingAs($user)->tool(GetPostTool::class, [])->assertHasErrors();
    WebsiteServer::actingAs($user)->tool(GetPostTool::class, ['ulid' => 'nope'])->assertHasErrors(['Post [nope] not found.']);
});

it('updates a post and its authors but never published_at', function (): void {
    $post = Post::factory()->create();
    $post->authors()->attach(User::factory()->create());
    $publishedAt = $post->published_at;
    $newAuthor = User::factory()->create();

    $data = toolJson(WebsiteServer::actingAs(userWithAbilities(Ability::PostsUpdate))
        ->tool(UpdatePostTool::class, [
            'ulid' => $post->ulid,
            'title' => 'New title',
            'author_ids' => [$newAuthor->id],
            'published_at' => null,
        ])
        ->assertOk());

    $post->refresh();

    expect($post->title)->toBe('New title')
        ->and($post->published_at?->toIso8601String())->toBe($publishedAt?->toIso8601String())
        ->and($post->authors->modelKeys())->toBe([$newAuthor->id])
        ->and($data['authors'][0]['id'])->toBe($newAuthor->id);
});

it('keeps the authors when update-post gets no author_ids', function (): void {
    $post = draftPost();
    $authorIds = $post->authors->modelKeys();

    WebsiteServer::actingAs(userWithAbilities(Ability::PostsUpdate))
        ->tool(UpdatePostTool::class, ['ulid' => $post->ulid, 'excerpt' => 'Changed'])
        ->assertOk();

    expect($post->refresh()->excerpt)->toBe('Changed')
        ->and($post->authors->modelKeys())->toBe($authorIds);
});

it('rejects an empty author list and a missing ulid on update-post', function (): void {
    $post = draftPost();
    $user = userWithAbilities(Ability::PostsUpdate);

    WebsiteServer::actingAs($user)->tool(UpdatePostTool::class, ['ulid' => $post->ulid, 'author_ids' => []])->assertHasErrors();
    WebsiteServer::actingAs($user)->tool(UpdatePostTool::class, ['title' => 'x'])->assertHasErrors();
});

it('denies update-post without posts:update', function (): void {
    $post = draftPost();

    WebsiteServer::actingAs(userWithAbilities(Ability::PostsView))
        ->tool(UpdatePostTool::class, ['ulid' => $post->ulid, 'title' => 'x'])
        ->assertHasErrors(['This action is unauthorized.']);
});

it('deletes a post', function (): void {
    $post = draftPost();

    WebsiteServer::actingAs(userWithAbilities(Ability::PostsDelete))
        ->tool(DeletePostTool::class, ['ulid' => $post->ulid])
        ->assertOk();

    expect(Post::query()->count())->toBe(0);
});

it('validates the ulid on delete-post and denies without posts:delete', function (): void {
    $post = draftPost();

    WebsiteServer::actingAs(userWithAbilities(Ability::PostsDelete))->tool(DeletePostTool::class, [])->assertHasErrors();
    WebsiteServer::actingAs(userWithAbilities(Ability::PostsUpdate))
        ->tool(DeletePostTool::class, ['ulid' => $post->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect(Post::query()->count())->toBe(1);
});

it('publishes and unpublishes a post with posts:publish', function (): void {
    $post = draftPost();
    $user = userWithAbilities(Ability::PostsPublish);

    $data = toolJson(WebsiteServer::actingAs($user)->tool(PublishPostTool::class, ['ulid' => $post->ulid])->assertOk());

    expect($data['is_published'])->toBeTrue()
        ->and($post->refresh()->published_at)->not->toBeNull();

    $data = toolJson(WebsiteServer::actingAs($user)->tool(UnpublishPostTool::class, ['ulid' => $post->ulid])->assertOk());

    expect($data['is_published'])->toBeFalse()
        ->and($post->refresh()->published_at)->toBeNull();
});

it('needs posts:publish for publish-post and unpublish-post', function (): void {
    $post = draftPost();
    $user = userWithAbilities(Ability::PostsView, Ability::PostsUpdate, Ability::PostsCreate);

    WebsiteServer::actingAs($user)->tool(PublishPostTool::class, ['ulid' => $post->ulid])
        ->assertHasErrors(['This action is unauthorized.']);
    WebsiteServer::actingAs($user)->tool(UnpublishPostTool::class, ['ulid' => $post->ulid])
        ->assertHasErrors(['This action is unauthorized.']);

    expect($post->refresh()->published_at)->toBeNull();
});

it('validates the ulid on publish-post and unpublish-post', function (): void {
    $user = userWithAbilities(Ability::PostsPublish);

    WebsiteServer::actingAs($user)->tool(PublishPostTool::class, [])->assertHasErrors();
    WebsiteServer::actingAs($user)->tool(UnpublishPostTool::class, [])->assertHasErrors();
});
