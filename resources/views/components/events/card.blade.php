@props([
    'event',
    'featured' => false,
    'show_rsvp_link' => true,
    'show_learn_more_link' => false,
])

<flux:callout variant="secondary" inline>
    <flux:callout.heading>
        {{ $event->name }}

        <flux:badge color="orange" size="sm" inset="top bottom">
            {{ $event->location->city }}
        </flux:badge>
    </flux:callout.heading>

    <flux:callout.text>
        {{ $event->description }}
    </flux:callout.text>

    <div @class([
    'flex flex-wrap text-base text-(--callout-text)',
    'gap-4 text-base' => $featured === true,
    'gap-2 text-sm' => $featured === false,
])>
        <div class="inline-flex items-center gap-1">
            <flux:icon name="map-pin" variant="micro" class="size-4"/>
            {{ $event->location->name }}
        </div>
        <div class="inline-flex items-center gap-1">
            <flux:icon name="calendar" variant="micro" class="size-4"/>
            {{ $event->displayPeriod() }}
        </div>
        @if ($featured)
            <div>
                🍕 Food &amp; drinks included
            </div>
        @endif
    </div>

    <x-slot name="actions">
        @if ($show_rsvp_link)
            <flux:button href="{{ $event->meetup_link }}"
                         target="_blank"
                         rel="noopener"
                         icon:trailing="arrow-up-right">
                RSVP on Luma
            </flux:button>
        @endif

        @if ($show_learn_more_link)
            <flux:button
                href="{{ $event->show_url }}"
                variant="ghost"
            >
                Learn more
            </flux:button>
        @endif
    </x-slot>
</flux:callout>
