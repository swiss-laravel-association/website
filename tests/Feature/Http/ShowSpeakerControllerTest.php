<?php

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('shows the speaker at its canonical slug-ulid url', function (): void {
    $speaker = Speaker::factory()->create(['name' => 'Ada Lovelace']);
    $speaker->talks()->attach(Talk::factory()->create(['title' => 'Scaling Eloquent Queries']));

    get($speaker->show_url)
        ->assertOk()
        ->assertSeeText('Ada Lovelace')
        ->assertSeeText('Scaling Eloquent Queries');
});

it('redirects a stale speaker slug to the canonical url with a 301', function (): void {
    $speaker = Speaker::factory()->create(['name' => 'Ada Lovelace']);

    get("/meetups/speakers/old-slug-{$speaker->ulid}")
        ->assertStatus(301)
        ->assertRedirect($speaker->show_url);
});

it('returns 404 for a non-ulid speaker tail', function (): void {
    get('/meetups/speakers/not-a-ulid')->assertNotFound();
});

it('returns 404 for a valid but unknown speaker ulid', function (): void {
    $orphanUlid = strtolower((string) Str::ulid());

    get("/meetups/speakers/ghost-{$orphanUlid}")->assertNotFound();
});
