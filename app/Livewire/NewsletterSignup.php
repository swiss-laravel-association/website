<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;
use Spatie\MailcoachSdk\Facades\Mailcoach;

class NewsletterSignup extends Component
{
    use UsesSpamProtection;

    public HoneypotData $extraFields;

    #[Validate(['required', 'min:2'])]
    public string $name = '';

    #[Validate(['required', 'email', 'unique:newsletter_subscribers,email'])]
    public string $email = '';

    #[Locked]
    public bool $subscribed = false;

    public function mount(): void
    {
        $this->extraFields = new HoneypotData;
    }

    public function submit(): void
    {
        $this->protectAgainstSpam();

        $this->validate();

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
