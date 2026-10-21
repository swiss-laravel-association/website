<?php

use App\Actions\Locations\CreateLocation;
use App\Actions\Locations\DeleteLocation;
use App\Actions\Locations\FindOrCreateLocation;
use App\Actions\Locations\UpdateLocation;
use App\Models\Event;
use App\Models\Location;

it('creates a location', function (): void {
    $location = app(CreateLocation::class)->handle([
        'name' => 'Kosmos',
        'address' => 'Lagerstrasse 104',
        'zip_code' => '8004',
        'city' => 'Zürich',
        'capacity' => 100,
    ]);

    expect($location)->toBeInstanceOf(Location::class)
        ->and($location->exists)->toBeTrue();
    $this->assertDatabaseHas('locations', ['name' => 'Kosmos', 'city' => 'Zürich', 'capacity' => 100]);
});

it('updates only the given keys', function (): void {
    $location = Location::factory()->create(['name' => 'Old', 'city' => 'Bern']);

    $updated = app(UpdateLocation::class)->handle($location, ['name' => 'New']);

    expect($updated->name)->toBe('New')
        ->and($updated->fresh()->city)->toBe('Bern');
});

it('deletes a location', function (): void {
    $location = Location::factory()->create();

    app(DeleteLocation::class)->handle($location);

    $this->assertModelMissing($location);
});

it('returns the existing location matching the name case-insensitively', function (): void {
    $existing = Location::factory()->create(['name' => 'Kosmos']);

    $found = app(FindOrCreateLocation::class)->handle('kosmos', ['city' => 'Elsewhere']);

    expect($found->is($existing))->toBeTrue()
        ->and(Location::count())->toBe(1)
        ->and($found->city)->not->toBe('Elsewhere');
});

it('creates a location with the given attributes when none matches', function (): void {
    $location = app(FindOrCreateLocation::class)->handle('Kosmos', [
        'address' => 'Lagerstrasse 104',
        'zip_code' => '8004',
        'city' => 'Zürich',
    ]);

    expect($location->exists)->toBeTrue()
        ->and($location->name)->toBe('Kosmos')
        ->and($location->city)->toBe('Zürich')
        ->and(Location::count())->toBe(1);
});

it('keeps events when their location is deleted and clears the location id', function (): void {
    $location = Location::factory()->create();
    $event = Event::factory()->create(['location_id' => $location->id]);

    app(DeleteLocation::class)->handle($location);

    $this->assertModelMissing($location);
    expect($event->fresh())->not->toBeNull()
        ->and($event->fresh()->location_id)->toBeNull();
});
