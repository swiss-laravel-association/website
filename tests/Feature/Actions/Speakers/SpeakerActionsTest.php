<?php

use App\Actions\Speakers\CreateSpeaker;
use App\Actions\Speakers\DeleteSpeaker;
use App\Actions\Speakers\UpdateSpeaker;
use App\Models\Speaker;
use App\Models\Talk;

it('creates a speaker', function (): void {
    $speaker = app(CreateSpeaker::class)->handle([
        'name' => 'Jane Doe',
        'bio' => 'Hello',
        'github_profile' => 'https://github.com/jane',
    ]);

    expect($speaker)->toBeInstanceOf(Speaker::class)
        ->and($speaker->exists)->toBeTrue();
    $this->assertDatabaseHas('speakers', ['name' => 'Jane Doe', 'bio' => 'Hello']);
});

it('updates only the given keys', function (): void {
    $speaker = Speaker::factory()->create(['name' => 'Old', 'bio' => 'Keep me']);

    $updated = app(UpdateSpeaker::class)->handle($speaker, ['name' => 'New']);

    expect($updated->name)->toBe('New')
        ->and($updated->fresh()->bio)->toBe('Keep me');
});

it('deletes a speaker', function (): void {
    $speaker = Speaker::factory()->create();

    app(DeleteSpeaker::class)->handle($speaker);

    $this->assertModelMissing($speaker);
});

it('detaches talks when deleting a speaker', function (): void {
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();
    $talk->speakers()->attach($speaker);

    app(DeleteSpeaker::class)->handle($speaker);

    $this->assertModelMissing($speaker);
    $this->assertModelExists($talk);
    $this->assertDatabaseCount('talk_speaker', 0);
});

it('ignores keys that are not speaker fields', function (): void {
    $speaker = app(CreateSpeaker::class)->handle(['name' => 'Jane', 'ulid' => 'forced-ulid']);

    expect($speaker->fresh()->ulid)->not->toBe('forced-ulid');

    app(UpdateSpeaker::class)->handle($speaker, ['name' => 'Janet', 'ulid' => 'forced-ulid']);

    expect($speaker->fresh()->ulid)->not->toBe('forced-ulid')
        ->and($speaker->fresh()->name)->toBe('Janet');
});
