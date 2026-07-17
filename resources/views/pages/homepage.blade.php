<x-app-layout>
    <section class="relative overflow-hidden pt-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">

            <div class="grid items-start gap-10 md:grid-cols-2">
                <div class="relative z-10">
                    <x-hero-eyebrow class="mb-4">Swiss Laravel Association</x-hero-eyebrow>

                    <h1 class="mb-4 text-[clamp(2rem,4vw,3rem)] font-medium leading-[1] text-mist-100">
                        Building the <strong class="font-serif italic font-medium text-white">Laravel</strong><br>
                        community across<br>
                        Switzerland.
                    </h1>

                    <p class="mb-6 max-w-[460px] text-base leading-[1.75] text-mist-400">
                        We organise the regular Laravel Switzerland Meetup in different cities —
                        bringing PHP and Laravel developers together over talks, drinks and good conversation.
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <flux:button :href="route('events.next-event')"
                                     variant="primary"
                                     target="_blank"
                                     rel="noopener"
                                     icon:trailing="arrow-up-right">
                           Join next meetup
                        </flux:button>

                        <flux:button href="https://www.youtube.com/@swiss-laravel-association"
                                     variant="ghost"
                                     target="_blank"
                                     rel="noopener"
                                     icon:trailing="arrow-up-right">
                            Watch past talks
                        </flux:button>
                    </div>
                </div>

                <div class="relative flex items-center justify-center h-full min-h-[340px]">
                    <x-sla-logo class="w-60 text-brand" />
                    <div class="absolute inset-0">
                        <x-hero-ascii-wrap />
                    </div>
                </div>

            </div>

            {{-- Next event strip --}}
            @if ($nextEvent)
                <div class="mt-16 mb-16">
                    <x-events.card :event="$nextEvent" :featured="true" show_learn_more_link="true" :show_rsvp_link="true" color="orange" />
                </div>
            @endif
        </div>
    </section>

    <x-dither-divider canvasId="ditherDivider1" :speed="0.010" />

    <section class="py-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">
            <x-section-label>Sponsors &amp; partners</x-section-label>

            <div class="flex flex-wrap items-center gap-4">

                @foreach($sponsors as $sponsor)
                    <a href="{{ route('association.sponsors') }}"
                       class="rounded-sm border border-mist-700 px-4 py-1.5 font-mono text-base tracking-[0.04em] text-mist-dark-9 transition-colors hover:border-mist-dark-7 hover:text-mist-dark-11"
                    >
                        {{ $sponsor->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="events" class="pb-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">

            <x-section-label>
                Upcoming events

                <x-slot:trailing>
                    <flux:link :href="route('events.index')" class="!text-mist-400 hover:!text-mist-200">
                        All events →
                    </flux:link>
                </x-slot:trailing>
            </x-section-label>

            <div class="grid grid-cols-1 gap-2.5 md:grid-cols-2">
                @foreach ($futureEvents as $event)
                    <x-events.card
                        :event="$event"
                        :show_learn_more_link="true"
                        :show_rsvp_link="true"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <x-dither-divider canvasId="ditherDivider1" :speed="0.010" />

    <x-meetup-impressions id="impressions" class="py-16" />

    <section id="past-events" class="pb-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">
            <x-section-label>
                Past events

                <x-slot:trailing>
                    <flux:link :href="route('events.index')" class="!text-mist-400 hover:!text-mist-200">
                        All events →
                    </flux:link>
                </x-slot:trailing>
            </x-section-label>

            <div class="grid grid-cols-1 gap-2.5 md:grid-cols-2">
                @foreach ($pastEvents as $event)
                    <x-events.card
                        :event="$event"
                        :show_learn_more_link="true"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <x-dither-divider canvasId="ditherDivider2" :speed="0.002" />

</x-app-layout>
