<x-app-layout>
    <section class="relative overflow-hidden pt-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">

            <div class="grid items-start gap-10 md:grid-cols-2">
                <div class="relative z-10">
                    <x-hero-eyebrow class="mb-4">Swiss Laravel Association</x-hero-eyebrow>

                    <h1 class="mb-4 text-[clamp(2rem,4vw,3rem)] font-light leading-tight text-mauve-100">
                        Building the <strong class="font-semibold text-white">Laravel</strong><br>
                        community across<br>
                        Switzerland.
                    </h1>

                    <p class="mb-6 max-w-[460px] text-base leading-[1.75] text-mauve-400">
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

                <x-hero-ascii-wrap />
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
                       class="rounded-sm border border-mauve-700 px-4 py-1.5 font-mono text-base tracking-[0.04em] text-mauve-dark-9 transition-colors hover:border-mauve-dark-7 hover:text-mauve-dark-11"
                    >
                        {{ $sponsor->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="events" class="pb-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">

            <x-section-label>Upcoming events</x-section-label>

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

    <section id="events" class="pb-16">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">
            <x-section-label>Past events</x-section-label>

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

    <section class="border-y border-mauve-700 bg-mauve-950 py-10">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">
            <div class="grid items-start gap-16 md:grid-cols-2">
                <div>
                    <h2 class="mb-2 text-3xl font-light text-mauve-50">
                        Stay in the loop.
                    </h2>
                    <p class="mb-4 text-sm text-mauve-300">
                        Get notified about upcoming meetups, new talk recordings and community news.
                        No spam, ever. Unsubscribe any time.
                    </p>

                    <form action="{{ route('links.newsletter') }}" method="get" class="flex flex-col gap-2 sm:flex-row items-end">
                        <flux:field>
                            <flux:label>E-Mail Address</flux:label>
                            <flux:input type="email" name="email" required placeholder="john@appleseed.com"/>
                            <flux:error name="username"/>
                        </flux:field>

                        <flux:button type="submit">
                            Subscribe
                        </flux:button>
                    </form>
                </div>

                <div id="socials">
                    <p class="mb-3 font-mono text-xs uppercase tracking-wide text-mauve-300">
                        {{ __('nav.follow_us') }}
                    </p>

                    <div class="flex flex-col gap-2">
                        <x-social-link platform="YouTube" url="{{ route('links.youtube') }}">Swiss Laravel Association</x-social-link>
                        <x-social-link platform="Mastodon" url="{{ route('links.mastodon') }}">@swiss_laravel_association@phpc.social</x-social-link>
                        <x-social-link platform="Bluesky" url="{{ route('links.bluesky') }}">@laravel.swiss</x-social-link>
                        <x-social-link platform="LinkedIn" url="{{ route('links.linkedin') }}">Swiss Laravel Association</x-social-link>
                        <x-social-link platform="Twitter/X" url="{{ route('links.x') }}">@swisslaravel</x-social-link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
