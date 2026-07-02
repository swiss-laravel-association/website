<?php

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');

    $this->upcomingEvent = Event::factory()->create([
        'start_date' => Carbon::now()->addWeek(),
    ]);

    $this->pastEvent = Event::factory()->create([
        'start_date' => Carbon::now()->subWeek(),
    ]);
});

it('scopes the upcoming configuration to future events', function (): void {
    $ids = EventResource::withConfiguration('upcoming', fn (): array => EventResource::getEloquentQuery()->pluck('id')->all());

    expect($ids)->toBe([$this->upcomingEvent->getKey()]);
});

it('scopes the past configuration to past events', function (): void {
    $ids = EventResource::withConfiguration('past', fn (): array => EventResource::getEloquentQuery()->pluck('id')->all());

    expect($ids)->toBe([$this->pastEvent->getKey()]);
});

it('does not scope the all configuration', function (): void {
    $ids = EventResource::withConfiguration('all', fn (): array => EventResource::getEloquentQuery()->pluck('id')->all());

    expect($ids)->toContain($this->upcomingEvent->getKey())
        ->toContain($this->pastEvent->getKey());
});

it('uses the configured navigation labels', function (): void {
    $allLabel = EventResource::withConfiguration('all', fn (): string => EventResource::getNavigationLabel());
    $upcomingLabel = EventResource::withConfiguration('upcoming', fn (): string => EventResource::getNavigationLabel());
    $pastLabel = EventResource::withConfiguration('past', fn (): string => EventResource::getNavigationLabel());

    expect($allLabel)->toBe('All Events')
        ->and($upcomingLabel)->toBe('Upcoming Events')
        ->and($pastLabel)->toBe('Past Events');
});

it('orders the navigation items deterministically', function (): void {
    $allSort = EventResource::withConfiguration('all', fn (): ?int => EventResource::getNavigationSort());
    $upcomingSort = EventResource::withConfiguration('upcoming', fn (): ?int => EventResource::getNavigationSort());
    $pastSort = EventResource::withConfiguration('past', fn (): ?int => EventResource::getNavigationSort());

    expect($allSort)->toBe(0)
        ->and($upcomingSort)->toBe(1)
        ->and($pastSort)->toBe(2);
});

it('shows all three event navigation items on the upcoming events page', function (): void {
    actingAs(User::factory()->create());

    $this->get(route('filament.admin.resources.upcoming-events.index'))
        ->assertOk()
        ->assertSeeInOrder(['All Events', 'Upcoming Events', 'Past Events']);
});

it('shows all three event navigation items on the past events page', function (): void {
    actingAs(User::factory()->create());

    $this->get(route('filament.admin.resources.past-events.index'))
        ->assertOk()
        ->assertSeeInOrder(['All Events', 'Upcoming Events', 'Past Events']);
});
