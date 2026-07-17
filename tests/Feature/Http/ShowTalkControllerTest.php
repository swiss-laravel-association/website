<?php

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('shows the talk at its canonical slug-ulid url', function (): void {
    $talk = Talk::factory()->create(['title' => 'Scaling Eloquent Queries']);
    $talk->speakers()->attach(Speaker::factory()->create(['name' => 'Ada Lovelace']));

    get($talk->show_url)
        ->assertOk()
        ->assertSeeText('Scaling Eloquent Queries')
        ->assertSeeText('Ada Lovelace');
});

it('redirects a stale talk slug to the canonical url with a 301', function (): void {
    $talk = Talk::factory()->create(['title' => 'Scaling Eloquent Queries']);

    get("/meetups/talks/old-slug-{$talk->ulid}")
        ->assertStatus(301)
        ->assertRedirect($talk->show_url);
});

it('returns 404 for a non-ulid talk tail', function (): void {
    get('/meetups/talks/not-a-ulid')->assertNotFound();
});

it('returns 404 for a valid but unknown talk ulid', function (): void {
    $orphanUlid = strtolower((string) Str::ulid());

    get("/meetups/talks/ghost-{$orphanUlid}")->assertNotFound();
});
