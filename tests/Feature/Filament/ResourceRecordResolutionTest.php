<?php

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Speakers\SpeakerResource;
use App\Filament\Resources\Talks\TalkResource;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    actingAs(User::factory()->create());
});

it('opens the Filament edit page for an event', function (): void {
    $event = Event::factory()->create();

    get(EventResource::getUrl('edit', ['record' => $event]))->assertOk();
});

it('opens the Filament edit page for a talk', function (): void {
    $talk = Talk::factory()->create();

    get(TalkResource::getUrl('edit', ['record' => $talk]))->assertOk();
});

it('opens the Filament edit page for a speaker', function (): void {
    $speaker = Speaker::factory()->create();

    get(SpeakerResource::getUrl('edit', ['record' => $speaker]))->assertOk();
});
