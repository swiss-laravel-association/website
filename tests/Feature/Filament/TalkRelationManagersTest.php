<?php

use App\Filament\Resources\Talks\Pages\EditTalk;
use App\Filament\Resources\Talks\RelationManagers\EventsRelationManager;
use App\Filament\Resources\Talks\RelationManagers\SpeakersRelationManager;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAs(User::factory()->create());
});

it('lists the events a talk is connected to', function (): void {
    $talk = Talk::factory()->create();
    $events = Event::factory()->count(2)->create();
    $talk->events()->attach($events);

    Livewire::test(EventsRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($events);
});

it('can attach an existing event to a talk', function (): void {
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();

    Livewire::test(EventsRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->callAction(TestAction::make(AttachAction::class)->table(), [
            'recordId' => $event->getKey(),
        ])
        ->assertHasNoActionErrors();

    expect($talk->events()->whereKey($event->getKey())->exists())->toBeTrue();
});

it('detaches an event without deleting it', function (): void {
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();
    $talk->events()->attach($event);

    Livewire::test(EventsRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->callAction(TestAction::make(DetachAction::class)->table($event))
        ->assertHasNoActionErrors();

    assertDatabaseHas('events', ['id' => $event->getKey()]);
    assertDatabaseMissing('event_talk', [
        'event_id' => $event->getKey(),
        'talk_id' => $talk->getKey(),
    ]);
});

it('lists the speakers a talk is connected to', function (): void {
    $talk = Talk::factory()->create();
    $speakers = Speaker::factory()->count(2)->create();
    $talk->speakers()->attach($speakers);

    Livewire::test(SpeakersRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($speakers);
});

it('can attach an existing speaker to a talk', function (): void {
    $talk = Talk::factory()->create();
    $speaker = Speaker::factory()->create();

    Livewire::test(SpeakersRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->callAction(TestAction::make(AttachAction::class)->table(), [
            'recordId' => $speaker->getKey(),
        ])
        ->assertHasNoActionErrors();

    expect($talk->speakers()->whereKey($speaker->getKey())->exists())->toBeTrue();
});

it('can create a speaker and attach it to the talk', function (): void {
    $talk = Talk::factory()->create();

    Livewire::test(SpeakersRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->callAction(TestAction::make(CreateAction::class)->table(), [
            'name' => 'Jane Speaker',
        ])
        ->assertHasNoActionErrors();

    expect($talk->speakers()->where('name', 'Jane Speaker')->exists())->toBeTrue();
});

it('detaches a speaker without deleting it', function (): void {
    $talk = Talk::factory()->create();
    $speaker = Speaker::factory()->create();
    $talk->speakers()->attach($speaker);

    Livewire::test(SpeakersRelationManager::class, [
        'ownerRecord' => $talk,
        'pageClass' => EditTalk::class,
    ])
        ->callAction(TestAction::make(DetachAction::class)->table($speaker))
        ->assertHasNoActionErrors();

    assertDatabaseHas('speakers', ['id' => $speaker->getKey()]);
    assertDatabaseMissing('talk_speaker', [
        'talk_id' => $talk->getKey(),
        'speaker_id' => $speaker->getKey(),
    ]);
});
