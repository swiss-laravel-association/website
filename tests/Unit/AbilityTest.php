<?php

use App\Enums\Ability;

it('has a non-empty label on every case', function (Ability $ability) {
    expect($ability->getLabel())->toBeString()->not->toBeEmpty();
})->with(Ability::cases());

it('uses resource:action values', function (Ability $ability) {
    expect($ability->value)->toMatch('/^[a-z]+:[a-z]+$/');
})->with(Ability::cases());

it('has the expected value for publishing events', function () {
    expect(Ability::EventsPublish->value)->toBe('events:publish');
    expect(Ability::PostsPublish->getLabel())->toBe('Publish posts');
    expect(Ability::EventsCreate->getLabel())->toBe('Create events');
});

it('has no talks:publish case', function () {
    Ability::from('talks:publish');
})->throws(ValueError::class);

it('groups abilities by resource label', function () {
    $grouped = Ability::groupedByResource();

    expect($grouped)->toHaveKeys(['Events', 'Talks', 'Speakers', 'Locations', 'Sponsors', 'Posts'])
        ->and($grouped['Events'])->toHaveKey('events:publish')
        ->and($grouped['Talks'])->not->toHaveKey('talks:publish')
        ->and($grouped['Events']['events:create'])->toBe('Create events');
});
