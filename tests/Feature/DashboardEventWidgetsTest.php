<?php

use App\Filament\Widgets\PastEventsWidget;
use App\Filament\Widgets\UpcomingEventsWidget;
use App\Models\Event;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');
    actingAs(User::factory()->create());
});

it('lists the next three upcoming events', function (): void {
    $soonest = Event::factory()->create(['start_date' => Carbon::now()->addDay()]);
    $second = Event::factory()->create(['start_date' => Carbon::now()->addDays(2)]);
    $third = Event::factory()->create(['start_date' => Carbon::now()->addDays(3)]);
    $furthest = Event::factory()->create(['start_date' => Carbon::now()->addDays(4)]);

    Livewire::test(UpcomingEventsWidget::class)
        ->assertCanSeeTableRecords([$soonest, $second, $third])
        ->assertCanNotSeeTableRecords([$furthest]);
});

it('does not list past events in the upcoming widget', function (): void {
    $past = Event::factory()->create(['start_date' => Carbon::now()->subDay()]);

    Livewire::test(UpcomingEventsWidget::class)
        ->assertCanNotSeeTableRecords([$past]);
});

it('lists the last three past events', function (): void {
    $oldest = Event::factory()->create(['start_date' => Carbon::now()->subDays(4)]);
    $third = Event::factory()->create(['start_date' => Carbon::now()->subDays(3)]);
    $second = Event::factory()->create(['start_date' => Carbon::now()->subDays(2)]);
    $mostRecent = Event::factory()->create(['start_date' => Carbon::now()->subDay()]);

    Livewire::test(PastEventsWidget::class)
        ->assertCanSeeTableRecords([$mostRecent, $second, $third])
        ->assertCanNotSeeTableRecords([$oldest]);
});

it('does not list upcoming events in the past widget', function (): void {
    $upcoming = Event::factory()->create(['start_date' => Carbon::now()->addDay()]);

    Livewire::test(PastEventsWidget::class)
        ->assertCanNotSeeTableRecords([$upcoming]);
});
