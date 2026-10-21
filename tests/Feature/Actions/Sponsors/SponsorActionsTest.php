<?php

use App\Actions\Sponsors\CreateSponsor;
use App\Actions\Sponsors\DeleteSponsor;
use App\Actions\Sponsors\UpdateSponsor;
use App\Enums\SponsorType;
use App\Models\Sponsor;

it('creates a sponsor', function (): void {
    $sponsor = app(CreateSponsor::class)->handle([
        'name' => 'Acme',
        'website' => 'https://acme.test',
        'type' => SponsorType::Location,
        'background_color' => '#ffffff',
    ]);

    expect($sponsor)->toBeInstanceOf(Sponsor::class)
        ->and($sponsor->exists)->toBeTrue()
        ->and($sponsor->type)->toBe(SponsorType::Location);
    $this->assertDatabaseHas('sponsors', ['name' => 'Acme', 'website' => 'https://acme.test']);
});

it('updates only the given keys', function (): void {
    $sponsor = Sponsor::factory()->create(['name' => 'Old', 'website' => 'https://keep.test']);

    $updated = app(UpdateSponsor::class)->handle($sponsor, ['name' => 'New']);

    expect($updated->name)->toBe('New')
        ->and($updated->fresh()->website)->toBe('https://keep.test');
});

it('deletes a sponsor', function (): void {
    $sponsor = Sponsor::factory()->create();

    app(DeleteSponsor::class)->handle($sponsor);

    $this->assertModelMissing($sponsor);
});

it('ignores keys that are not sponsor fields', function (): void {
    $sponsor = app(CreateSponsor::class)->handle([
        'name' => 'Acme',
        'website' => 'https://acme.test',
        'type' => SponsorType::Founding,
        'ulid' => 'forced-ulid',
    ]);

    expect($sponsor->fresh()->ulid)->not->toBe('forced-ulid');

    app(UpdateSponsor::class)->handle($sponsor, ['name' => 'New', 'ulid' => 'forced-ulid']);

    expect($sponsor->fresh()->ulid)->not->toBe('forced-ulid')
        ->and($sponsor->fresh()->name)->toBe('New');
});
