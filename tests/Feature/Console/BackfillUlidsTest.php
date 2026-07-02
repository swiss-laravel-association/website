<?php

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

it('backfills a ulid for rows that are missing one', function (): void {
    // Create an event via factory to get valid data, but insert directly to skip ULID generation
    $data = Event::factory()->make()->toArray();
    unset($data['created_at'], $data['updated_at']); // Remove timestamps, they'll be added by DB
    DB::table('events')->insert([...$data, 'created_at' => now(), 'updated_at' => now()]);

    $event = Event::latest('id')->first();
    expect($event->ulid)->toBeNull();

    $this->artisan('ulids:backfill')->assertSuccessful();

    $ulid = $event->fresh()->ulid;
    expect($ulid)->not->toBeNull()
        ->and(Str::isUlid($ulid))->toBeTrue()
        ->and($ulid)->toBe(strtolower($ulid));
});

it('leaves existing ulids untouched', function (): void {
    $event = Event::factory()->create();
    $originalUlid = $event->ulid;

    $this->artisan('ulids:backfill')->assertSuccessful();

    expect($event->fresh()->ulid)->toBe($originalUlid);
});
