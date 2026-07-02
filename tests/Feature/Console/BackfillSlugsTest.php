<?php

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Support\Facades\DB;

it('backfills slugs for rows that are missing one', function (): void {
    // Insert directly to bypass model events so no slug is generated.
    DB::table('events')->insert([
        'name' => 'Laravel Zurich Meetup',
        'description' => 'x',
        'start_date' => now(),
        'end_date' => now(),
        'is_published' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('talks')->insert([
        'title' => 'Scaling Eloquent Queries',
        'description' => 'x',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('speakers')->insert([
        'name' => 'Ada Lovelace',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect(Event::first()->slug)->toBeNull()
        ->and(Talk::first()->slug)->toBeNull()
        ->and(Speaker::first()->slug)->toBeNull();

    $this->artisan('slugs:backfill')->assertSuccessful();

    expect(Event::first()->slug)->toBe('laravel-zurich-meetup')
        ->and(Talk::first()->slug)->toBe('scaling-eloquent-queries')
        ->and(Speaker::first()->slug)->toBe('ada-lovelace');
});

it('leaves existing slugs untouched', function (): void {
    $event = Event::factory()->create(['name' => 'Original Name']);
    $originalSlug = $event->slug;

    $this->artisan('slugs:backfill')->assertSuccessful();

    expect($event->fresh()->slug)->toBe($originalSlug);
});
