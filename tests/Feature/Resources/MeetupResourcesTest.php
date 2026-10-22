<?php

use App\Http\Resources\EventResource;
use App\Http\Resources\TalkResource;
use App\Models\Event;
use App\Models\Location;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Http\Resources\Json\JsonResource;

function asJson(JsonResource $resource): array
{
    return json_decode(json_encode($resource), true);
}

it('leaves out relations that are not loaded', function (): void {
    $event = Event::factory()->for(Location::factory())->create();

    $payload = asJson(EventResource::make($event));

    expect($payload)->not->toHaveKeys(['location', 'talks'])
        ->and($payload['location_ulid'])->toBe($event->location->ulid)
        ->and($payload['start_date'])->toMatch('/[+-]\d{2}:\d{2}$/');
});

it('nests loaded relations without pivot data', function (): void {
    $talk = Talk::factory()->hasAttached(Speaker::factory())->create();
    $event = Event::factory()->for(Location::factory())->create();
    $event->talks()->attach($talk);

    $payload = asJson(EventResource::make($event->load(['location', 'talks.speakers'])));

    expect($payload['location'])->toBeArray()
        ->and($payload['talks'])->toHaveCount(1)
        ->and($payload['talks'][0])->toHaveKey('url')
        ->and($payload['talks'][0]['speakers'])->toHaveCount(1)
        ->and($payload['talks'][0]['speakers'][0])->not->toHaveKey('pivot');
});

it('includes events on a talk only when loaded', function (): void {
    $talk = Talk::factory()->create();

    expect(asJson(TalkResource::make($talk)))->not->toHaveKeys(['speakers', 'events']);
});
