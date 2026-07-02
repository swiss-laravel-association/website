<?php

use App\Filament\Resources\Speakers\Pages\EditSpeaker;
use App\Filament\Resources\Speakers\RelationManagers\TalksRelationManager;
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

it('lists the talks of a speaker', function (): void {
    $speaker = Speaker::factory()->create();
    $talks = Talk::factory()->count(3)->create();
    $speaker->talks()->attach($talks);

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $speaker,
        'pageClass' => EditSpeaker::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($talks);
});

it('can attach an existing talk to a speaker', function (): void {
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $speaker,
        'pageClass' => EditSpeaker::class,
    ])
        ->callAction(TestAction::make(AttachAction::class)->table(), [
            'recordId' => $talk->getKey(),
        ])
        ->assertHasNoActionErrors();

    expect($speaker->talks()->whereKey($talk->getKey())->exists())->toBeTrue();
});

it('can create a talk and attach it to the speaker', function (): void {
    $speaker = Speaker::factory()->create();

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $speaker,
        'pageClass' => EditSpeaker::class,
    ])
        ->callAction(TestAction::make(CreateAction::class)->table(), [
            'title' => 'Testing Filament Relation Managers',
            'description' => 'A talk about relation managers.',
        ])
        ->assertHasNoActionErrors();

    expect($speaker->talks()->where('title', 'Testing Filament Relation Managers')->exists())->toBeTrue();
});

it('detaches a talk without deleting it', function (): void {
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();
    $speaker->talks()->attach($talk);

    Livewire::test(TalksRelationManager::class, [
        'ownerRecord' => $speaker,
        'pageClass' => EditSpeaker::class,
    ])
        ->callAction(TestAction::make(DetachAction::class)->table($talk))
        ->assertHasNoActionErrors();

    assertDatabaseHas('talks', ['id' => $talk->getKey()]);
    assertDatabaseMissing('talk_speaker', [
        'talk_id' => $talk->getKey(),
        'speaker_id' => $speaker->getKey(),
    ]);
});
