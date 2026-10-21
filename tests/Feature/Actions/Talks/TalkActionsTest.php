<?php

use App\Actions\Talks\AttachSpeakerToTalk;
use App\Actions\Talks\AttachTalkToEvent;
use App\Actions\Talks\CreateTalk;
use App\Actions\Talks\DeleteTalk;
use App\Actions\Talks\DetachSpeakerFromTalk;
use App\Actions\Talks\DetachTalkFromEvent;
use App\Actions\Talks\UpdateTalk;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;

it('creates a talk', function (): void {
    $talk = app(CreateTalk::class)->handle([
        'title' => 'Octane in Production',
        'description' => 'A talk.',
        'recording_url' => 'https://example.com/video',
    ]);

    expect($talk->exists)->toBeTrue();
    $this->assertDatabaseHas('talks', ['title' => 'Octane in Production']);
});

it('updates only the given keys', function (): void {
    $talk = Talk::factory()->create(['title' => 'Old', 'description' => 'Keep']);

    $updated = app(UpdateTalk::class)->handle($talk, ['title' => 'New']);

    expect($updated->title)->toBe('New')
        ->and($updated->fresh()->description)->toBe('Keep');
});

it('deletes a talk that has speakers and events', function (): void {
    $talk = Talk::factory()->create();
    $speaker = Speaker::factory()->create();
    $event = Event::factory()->create();
    $talk->speakers()->attach($speaker);
    $talk->events()->attach($event);

    app(DeleteTalk::class)->handle($talk);

    $this->assertModelMissing($talk);
    $this->assertModelExists($speaker);
    $this->assertModelExists($event);
    $this->assertDatabaseCount('talk_speaker', 0);
    $this->assertDatabaseCount('event_talk', 0);
});

it('attaches a talk to an event once', function (): void {
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();

    app(AttachTalkToEvent::class)->handle($talk, $event);
    app(AttachTalkToEvent::class)->handle($talk, $event);

    $this->assertDatabaseCount('event_talk', 1);
    expect($event->talks()->count())->toBe(1);
});

it('detaches a talk from an event', function (): void {
    $talk = Talk::factory()->create();
    $event = Event::factory()->create();
    $event->talks()->attach($talk);

    app(DetachTalkFromEvent::class)->handle($talk, $event);

    $this->assertDatabaseCount('event_talk', 0);
    $this->assertModelExists($talk);
});

it('attaches a speaker to a talk once', function (): void {
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();

    app(AttachSpeakerToTalk::class)->handle($speaker, $talk);
    app(AttachSpeakerToTalk::class)->handle($speaker, $talk);

    $this->assertDatabaseCount('talk_speaker', 1);
});

it('detaches a speaker from a talk', function (): void {
    $speaker = Speaker::factory()->create();
    $talk = Talk::factory()->create();
    $talk->speakers()->attach($speaker);

    app(DetachSpeakerFromTalk::class)->handle($speaker, $talk);

    $this->assertDatabaseCount('talk_speaker', 0);
    $this->assertModelExists($speaker);
});
