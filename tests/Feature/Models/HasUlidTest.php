<?php

use App\Models\Event;
use App\Models\Location;
use App\Models\Post;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\Talk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** @return array<int, class-string<Model>> */
function ulidModels(): array
{
    return [Event::class, Location::class, Post::class, Speaker::class, Sponsor::class, Talk::class];
}

it('generates a valid ulid when creating a model', function (string $modelClass): void {
    $model = $modelClass::factory()->create();

    expect($model->ulid)->not->toBeNull()
        ->and(Str::isUlid($model->ulid))->toBeTrue();
})->with(ulidModels());

it('keeps the integer primary key auto-incrementing', function (string $modelClass): void {
    $model = $modelClass::factory()->create();

    expect($model->getIncrementing())->toBeTrue()
        ->and($model->getKeyType())->toBe('int')
        ->and($model->id)->toBeInt();
})->with(ulidModels());

it('preserves a manually provided ulid', function (): void {
    $ulid = (string) Str::ulid();
    $event = Event::factory()->create(['ulid' => $ulid]);

    expect($event->ulid)->toBe($ulid);
});
