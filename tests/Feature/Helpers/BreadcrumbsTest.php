<?php

use App\Helpers\Breadcrumbs;

it('always starts with a home crumb', function (): void {
    $items = Breadcrumbs::make()->toArray();

    expect($items)->toHaveCount(1)
        ->and($items[0])->toMatchArray([
            'label' => 'Home',
            'url' => route('home'),
            'icon' => 'home',
        ]);
});

it('appends crumbs in order via the fluent api', function (): void {
    $items = Breadcrumbs::make()
        ->add('Blog', route('blog.index'))
        ->add('Announcing the SLA')
        ->toArray();

    expect($items)->toHaveCount(3)
        ->and($items[1])->toMatchArray([
            'label' => 'Blog',
            'url' => route('blog.index'),
        ])
        ->and($items[1])->not->toHaveKey('icon')
        ->and($items[2])->toBe(['label' => 'Announcing the SLA']);
});

it('omits the url and icon keys when they are not provided', function (): void {
    $items = Breadcrumbs::make()->add('Events')->toArray();

    expect($items[1])->toBe(['label' => 'Events']);
});
