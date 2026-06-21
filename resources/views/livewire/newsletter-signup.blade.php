<div>



    @if($subscribed)
        <flux:callout variant="success" icon="check-circle"
                      heading="You have subscribed successfully. Please check your inbox."/>
    @else
        <form wire:submit.prevent="submit" class="space-y-3">
            <flux:input label="Name" wire:model="name" required badge="Required"/>
            <flux:input label="E-Mail Address" wire:model="email" required badge="Required"/>

            @if (config('website.turnstile_enabled'))
                <x-turnstile wire:model="turnstileResponse"
                             data-action="newsletter"
                             data-appearance="interaction-only"/>
                <flux:error name="turnstileResponse"/>
            @endif

            <flux:button type="submit">
                Subscribe
            </flux:button>
        </form>
    @endif

    </div>

@assets
    @if (config('website.turnstile_enabled'))
        <x-turnstile.scripts />
    @endif
@endassets
