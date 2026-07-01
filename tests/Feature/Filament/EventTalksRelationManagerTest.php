<?php

use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\RelationManagers\TalksRelationManager;
use App\Models\Event;
use App\Models\Talk;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAs(User::factory()->create());
});

it('lists the talks of an event', function (): void {
    $event = Event::factory()->create();
    $talks = Talk::factory()->count(3)->create();
    $event->talks()->attach($talks);

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => EditEvent::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($talks);
});

it('can attach an existing talk to an event', function (): void {
    $event = Event::factory()->create();
    $talk = Talk::factory()->create();

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => EditEvent::class,
    ])
        ->callAction(TestAction::make(AttachAction::class)->table(), [
            'recordId' => $talk->getKey(),
        ])
        ->assertHasNoActionErrors();

    expect($event->talks()->whereKey($talk->getKey())->exists())->toBeTrue();
});

it('detaches a talk without deleting it', function (): void {
    $event = Event::factory()->create();
    $talk = Talk::factory()->create();
    $event->talks()->attach($talk);

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $event,
        'pageClass' => EditEvent::class,
    ])
        ->callAction(TestAction::make(DetachAction::class)->table($talk))
        ->assertHasNoActionErrors();

    assertDatabaseHas('talks', ['id' => $talk->getKey()]);
    assertDatabaseMissing('event_talk', [
        'event_id' => $event->getKey(),
        'talk_id' => $talk->getKey(),
    ]);
});
