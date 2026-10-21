<?php

use App\Actions\Posts\CreatePost;
use App\Actions\Posts\DeletePost;
use App\Actions\Posts\PublishPost;
use App\Actions\Posts\UnpublishPost;
use App\Actions\Posts\UpdatePost;
use App\Models\Post;
use App\Models\User;

it('creates an unpublished post with authors and a slug from the title', function (): void {
    $author = User::factory()->create();

    $post = app(CreatePost::class)->handle([
        'title' => 'Hello Laravel World',
        'content' => 'Body',
        'excerpt' => 'Short',
    ], [$author->id]);

    expect($post->exists)->toBeTrue()
        ->and($post->slug)->toBe('hello-laravel-world')
        ->and($post->published_at)->toBeNull()
        ->and($post->authors->modelKeys())->toBe([$author->id]);
});

it('ignores published_at and slug when creating a post', function (): void {
    $author = User::factory()->create();

    $post = app(CreatePost::class)->handle([
        'title' => 'Fresh Title',
        'content' => 'Body',
        'excerpt' => 'Short',
        'published_at' => now(),
        'slug' => 'custom-slug',
    ], [$author->id]);

    expect($post->fresh()->published_at)->toBeNull()
        ->and($post->fresh()->slug)->toBe('fresh-title');
});

it('rejects creating a post without authors', function (): void {
    app(CreatePost::class)->handle([
        'title' => 'No Authors',
        'content' => 'Body',
        'excerpt' => 'Short',
    ], []);
})->throws(InvalidArgumentException::class);

it('updates only the given keys and keeps authors when none are given', function (): void {
    $author = User::factory()->create();
    $post = Post::factory()->create(['title' => 'Old', 'excerpt' => 'Keep']);
    $post->authors()->attach($author);

    $updated = app(UpdatePost::class)->handle($post, ['title' => 'New']);

    expect($updated->title)->toBe('New')
        ->and($updated->fresh()->excerpt)->toBe('Keep')
        ->and($updated->authors()->pluck('users.id')->all())->toBe([$author->id]);
});

it('ignores published_at when updating an unpublished post', function (): void {
    $post = Post::factory()->create(['published_at' => null]);

    app(UpdatePost::class)->handle($post, ['title' => 'Changed', 'published_at' => now()]);

    expect($post->fresh()->published_at)->toBeNull();
});

it('ignores published_at when updating a published post', function (): void {
    $original = now()->subYear()->startOfDay();
    $post = Post::factory()->create(['published_at' => $original]);

    app(UpdatePost::class)->handle($post, ['title' => 'Changed', 'published_at' => null]);

    expect($post->fresh()->published_at->equalTo($original))->toBeTrue();
});

it('ignores slug when updating a post', function (): void {
    $post = Post::factory()->create(['title' => 'Original Title']);

    app(UpdatePost::class)->handle($post, ['content' => 'New body', 'slug' => 'custom-slug']);

    expect($post->fresh()->slug)->not->toBe('custom-slug')
        ->and($post->fresh()->content)->toBe('New body');
});

it('syncs authors when given', function (): void {
    [$first, $second] = User::factory()->count(2)->create();
    $post = Post::factory()->create();
    $post->authors()->attach($first);

    app(UpdatePost::class)->handle($post, [], [$second->id]);

    expect($post->authors()->pluck('users.id')->all())->toBe([$second->id]);
});

it('rejects syncing to an empty author list', function (): void {
    $post = Post::factory()->create();

    app(UpdatePost::class)->handle($post, [], []);
})->throws(InvalidArgumentException::class);

it('deletes a post with authors', function (): void {
    $author = User::factory()->create();
    $post = Post::factory()->create();
    $post->authors()->attach($author);

    app(DeletePost::class)->handle($post);

    $this->assertModelMissing($post);
    $this->assertModelExists($author);
    $this->assertDatabaseCount('post_user', 0);
});

it('publishes a post', function (): void {
    $post = Post::factory()->create(['published_at' => null]);

    $published = app(PublishPost::class)->handle($post);

    expect($published->published_at)->not->toBeNull()
        ->and($post->fresh()->published_at)->not->toBeNull();
});

it('keeps the original published_at when publishing an already published post', function (): void {
    $original = now()->subYear()->startOfDay();
    $post = Post::factory()->create(['published_at' => $original]);

    $published = app(PublishPost::class)->handle($post);

    expect($published->fresh()->published_at->equalTo($original))->toBeTrue();
});

it('unpublishes a post', function (): void {
    $post = Post::factory()->create();

    $unpublished = app(UnpublishPost::class)->handle($post);

    expect($unpublished->published_at)->toBeNull()
        ->and($post->fresh()->published_at)->toBeNull();
});

it('publishes a scheduled post now', function (): void {
    $post = Post::factory()->create(['published_at' => now()->addWeek()]);

    $published = app(PublishPost::class)->handle($post);

    expect($published->fresh()->published_at->isPast())->toBeTrue();
});
