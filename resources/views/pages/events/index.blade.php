<x-app-layout>
    <div class="container mx-auto max-w-6xl px-6 pt-8 lg:px-10">
        <x-breadcrumbs :items="$breadcrumbs" />
    </div>

    @if ($nextEvent)
        <section class="relative overflow-hidden pt-8 pb-16">
            <div class="container mx-auto max-w-6xl px-6 lg:px-10">
                <x-hero-eyebrow class="mb-4">Next meetup</x-hero-eyebrow>

                <h1 class="mb-8 text-[clamp(2rem,4vw,3rem)] font-light leading-tight text-mist-100">
                    Join us at our next event.
                </h1>

                <x-events.card :event="$nextEvent" :featured="true" :show_rsvp_link="true" :show_learn_more_link="true" />
            </div>
        </section>

        <x-dither-divider canvasId="ditherEventsHero" :speed="0.010" />
    @else
        <section class="relative overflow-hidden pt-8 pb-12">
            <div class="container mx-auto max-w-6xl px-6 lg:px-10">
                <x-hero-eyebrow class="mb-4">Meetups</x-hero-eyebrow>

                <h1 class="mb-4 text-[clamp(2rem,4vw,3rem)] font-light leading-tight text-mist-100">
                    No event is scheduled right now.
                </h1>

                <p class="max-w-[460px] text-base leading-[1.75] text-mist-400">
                    We are working on the next meetup. In the meantime, browse our past events below.
                </p>
            </div>
        </section>
    @endif

    @if ($upcomingEvents->isNotEmpty())
        <section class="py-16">
            <div class="container mx-auto max-w-6xl px-6 lg:px-10">
                <x-section-label>Upcoming events</x-section-label>

                <div class="grid grid-cols-1 gap-2.5 md:grid-cols-2">
                    @foreach ($upcomingEvents as $event)
                        <x-events.card
                            :event="$event"
                            :show_learn_more_link="true"
                            :show_rsvp_link="true"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-meetup-impressions class="pb-16" />

    <section class="pb-16 {{ $nextEvent ? '' : 'pt-4' }}">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">
            <x-section-label>Past events</x-section-label>

            @if ($pastEvents->isEmpty())
                <p class="text-mist-400">No past events yet.</p>
            @else
                <div class="grid grid-cols-1 gap-2.5 md:grid-cols-2">
                    @foreach ($pastEvents as $event)
                        <x-events.card
                            :event="$event"
                            :show_learn_more_link="true"
                            :show_rsvp_link="false"
                        />
                    @endforeach
                </div>

                <div class="mt-10">
                    <flux:pagination :paginator="$pastEvents" />
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
