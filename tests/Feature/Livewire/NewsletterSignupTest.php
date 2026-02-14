<?php

use App\Livewire\NewsletterSignup;
use App\Models\NewsletterSubscriber;
use RyanChandler\LaravelCloudflareTurnstile\Facades\Turnstile;
use Spatie\MailcoachSdk\Facades\Mailcoach;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Mailcoach::fake();
    config([
        'website.turnstile_enabled' => true,
    ]);
});

it('renders newsletter sign up successfully', function (): void {
    Livewire::test(NewsletterSignup::class)
        ->assertStatus(200);
});

it('subscribes user to newsletter', function (): void {
    Turnstile::fake();

    Livewire::test(NewsletterSignup::class)
        ->set('name', 'John Doe')
        ->set('email', 'john.doe@example.com')
        ->set('turnstileResponse', Turnstile::dummy())
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('subscribed', true);

    assertDatabaseHas(NewsletterSubscriber::class, [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
    ]);
});

it('throws validation error if name, email and turnstile fails', function () {
    Turnstile::fake()->fail();

    Livewire::test(NewsletterSignup::class)
        ->set('name', '')
        ->set('email', 'john.doe')
        ->set('turnstileResponse', Turnstile::dummy())
        ->call('submit')
        ->assertHasErrors([
            'name' => 'required',
            'email' => 'email',
            'turnstileResponse' => \RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile::class,
        ])
        ->assertSet('subscribed', false);

    assertDatabaseCount(NewsletterSubscriber::class, 0);
});

it('throws validation error if email already exists', function () {
    Turnstile::fake();

    NewsletterSubscriber::factory()->create([
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
    ]);

    Livewire::test(NewsletterSignup::class)
        ->set('name', 'John Doe')
        ->set('email', 'john.doe@example.com')
        ->set('turnstileResponse', Turnstile::dummy())
        ->call('submit')
        ->assertHasErrors([
            'email' => 'unique',
        ])
        ->assertSet('subscribed', false);

    assertDatabaseCount(NewsletterSubscriber::class, 1);
});
