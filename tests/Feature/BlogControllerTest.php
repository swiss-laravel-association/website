<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('loads blog index route and shows empty state message', function () {
    get(route('blog.index'))
        ->assertOk()
        ->assertSeeText('Looks like there are no posts yet. Check back later!');
});

it('loads blog index route and lists recent blog posts', function () {
    $posts = Post::factory()->count(3)->create();

    $response = get(route('blog.index'))
        ->assertOk()
        ->assertDontSeeText('Looks like there are no posts yet. Check back later!');

    $posts->each(function (Post $post) use ($response) {
        $response->assertSee($post->title);
    });
});

it('loads blog index route and only listes published posts', function () {
    $posts = Post::factory()->count(3)->create([
        'published_at' => now()->addDay(),
    ]);

    $response = get(route('blog.index'))
        ->assertOk()
        ->assertSeeText('Looks like there are no posts yet. Check back later!');

    $posts->each(function (Post $post) use ($response) {
        $response->assertDontSee($post->title);
    });
});

it('loads blog show route for the given blog post', function () {
    /** @var Post $post */
    $post = Post::factory()->create([
        'published_at' => now()->subDays(4),
    ]);

    get(route('blog.show', ['post' => $post]))
        ->assertOk()
        ->assertSeeText($post->title)
        ->assertSeeHtml($post->parsed_content);
});

it('throws error if user tries to access not published blog post ', function () {
    /** @var Post $post */
    $post = Post::factory()->create([
        'published_at' => now()->addDay(),
    ]);

    get(route('blog.show', ['post' => $post]))
        ->assertForbidden()
        ->assertDontSeeText($post->title)
        ->assertDontSeeHtml($post->parsed_content);
});
