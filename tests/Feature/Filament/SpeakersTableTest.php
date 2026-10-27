<?php

use App\Filament\Resources\Speakers\Pages\ListSpeakers;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    actingAs(User::factory()->create());
});

it('shows the number of talks per speaker', function (): void {
    $speakerWithTalks = Speaker::factory()->create();
    $speakerWithTalks->talks()->attach(Talk::factory()->count(2)->create());
    $speakerWithoutTalks = Speaker::factory()->create();

    Livewire::test(ListSpeakers::class)
        ->assertTableColumnStateSet('talks_count', 2, $speakerWithTalks)
        ->assertTableColumnStateSet('talks_count', 0, $speakerWithoutTalks);
});

it('can sort speakers by number of talks', function (): void {
    $speakerWithTalks = Speaker::factory()->create();
    $speakerWithTalks->talks()->attach(Talk::factory()->count(2)->create());
    $speakerWithoutTalks = Speaker::factory()->create();

    Livewire::test(ListSpeakers::class)
        ->sortTable('talks_count', 'desc')
        ->assertCanSeeTableRecords([$speakerWithTalks, $speakerWithoutTalks], inOrder: true);
});
