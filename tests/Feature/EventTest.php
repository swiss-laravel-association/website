<?php

use App\Models\Event;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;

function makeEvent(string $start, string $end): Event
{
    return (new Event)->forceFill([
        'start_date' => CarbonImmutable::parse($start),
        'end_date' => CarbonImmutable::parse($end),
    ]);
}

it('exposes the event period as a CarbonPeriodImmutable', function (): void {
    $event = makeEvent('2026-06-21 18:00:00', '2026-06-21 22:00:00');

    expect($event->period)
        ->toBeInstanceOf(CarbonPeriodImmutable::class)
        ->and($event->period->getStartDate()->equalTo($event->start_date))->toBeTrue()
        ->and($event->period->getEndDate()->equalTo($event->end_date))->toBeTrue();
});

it('formats the display period for a single-day event with date once', function (): void {
    $event = makeEvent('2026-06-21 18:00:00', '2026-06-21 22:00:00');

    expect($event->displayPeriod())->toBe('21.06.2026 18:00 - 22:00');
});

it('formats the display period for a multi-day event with both dates', function (): void {
    $event = makeEvent('2026-06-21 18:00:00', '2026-06-22 14:00:00');

    expect($event->displayPeriod())->toBe('21.06.2026 18:00 - 22.06.2026 14:00');
});
