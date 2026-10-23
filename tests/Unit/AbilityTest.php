<?php

use App\Enums\Ability;

it('has a non-empty label on every case', function (Ability $ability): void {
    expect($ability->getLabel())->toBeString()->not->toBeEmpty();
})->with(Ability::cases());

it('uses resource:action values', function (Ability $ability): void {
    expect($ability->value)->toMatch('/^[a-z]+:[a-z]+$/');
})->with(Ability::cases());

it('has the expected value for publishing events', function (): void {
    expect(Ability::EventsPublish->value)->toBe('events:publish');
    expect(Ability::PostsPublish->getLabel())->toBe('Publish posts');
    expect(Ability::EventsCreate->getLabel())->toBe('Create events');
});

it('has no talks:publish case', function (): void {
    Ability::from('talks:publish');
})->throws(ValueError::class);

it('groups abilities by resource label', function (): void {
    $grouped = Ability::groupedByResource();

    expect($grouped)->toHaveKeys(['Events', 'Talks', 'Speakers', 'Locations', 'Sponsors', 'Posts'])
        ->and($grouped['Events'])->toHaveKey('events:publish')
        ->and($grouped['Talks'])->not->toHaveKey('talks:publish')
        ->and($grouped['Events']['events:create'])->toBe('Create events');
});

it('has exactly 26 cases', function (): void {
    expect(Ability::cases())->toHaveCount(26);
});

it('has only events and posts publish abilities', function (): void {
    $publish = collect(Ability::cases())
        ->filter(fn (Ability $ability): bool => str_ends_with($ability->value, ':publish'))
        ->map(fn (Ability $ability): string => $ability->value)
        ->values()
        ->all();

    expect($publish)->toBe(['events:publish', 'posts:publish']);
});

it('has unique labels', function (): void {
    $labels = array_map(fn (Ability $ability): string => $ability->getLabel(), Ability::cases());

    expect($labels)->toHaveCount(count(array_unique($labels)));
});
