<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Spatie\MailcoachSdk\Facades\Mailcoach;

class NewsletterSignup extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $turnstileResponse = null;

    #[Locked]
    public bool $subscribed = false;

    public function submit(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'unique:newsletter_subscribers,email'],
            'turnstileResponse' => config('website.turnstile_enabled') ? ['required', new Turnstile] : ['nullable'],
        ]);

        NewsletterSubscriber::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        Mailcoach::createSubscriber(
            emailListUuid: config('mailcoach-sdk.lists.newsletter.uuid'),
            attributes: [
                'email' => $this->email,
                'first_name' => $this->name,
            ]);

        $this->subscribed = true;
    }

    public function render(): View
    {
        return view('livewire.newsletter-signup');
    }
}
