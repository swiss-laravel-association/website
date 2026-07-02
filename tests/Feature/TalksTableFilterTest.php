<?php

use App\Filament\Resources\Talks\Pages\ListTalks;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    actingAs(User::factory()->create());
});

it('can filter talks by speaker', function (): void {
    $speaker = Speaker::factory()->create();
    $talkWithSpeaker = Talk::factory()->create();
    $talkWithSpeaker->speakers()->attach($speaker);
    $otherTalk = Talk::factory()->create();

    Livewire::test(ListTalks::class)
        ->assertCanSeeTableRecords([$talkWithSpeaker, $otherTalk])
        ->filterTable('speakers', [$speaker->id])
        ->assertCanSeeTableRecords([$talkWithSpeaker])
        ->assertCanNotSeeTableRecords([$otherTalk]);
});

it('can filter talks by event', function (): void {
    $event = Event::factory()->create();
    $talkWithEvent = Talk::factory()->create();
    $talkWithEvent->events()->attach($event);
    $otherTalk = Talk::factory()->create();

    Livewire::test(ListTalks::class)
        ->assertCanSeeTableRecords([$talkWithEvent, $otherTalk])
        ->filterTable('events', [$event->id])
        ->assertCanSeeTableRecords([$talkWithEvent])
        ->assertCanNotSeeTableRecords([$otherTalk]);
});

it('can filter talks by recording availability', function (): void {
    $withRecording = Talk::factory()->create(['recording_url' => 'https://example.com/video']);
    $withoutRecording = Talk::factory()->create(['recording_url' => null]);

    Livewire::test(ListTalks::class)
        ->filterTable('recording_url', true)
        ->assertCanSeeTableRecords([$withRecording])
        ->assertCanNotSeeTableRecords([$withoutRecording])
        ->filterTable('recording_url', false)
        ->assertCanSeeTableRecords([$withoutRecording])
        ->assertCanNotSeeTableRecords([$withRecording]);
});
