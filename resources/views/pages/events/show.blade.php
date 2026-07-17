<x-app-layout>
    <div class="container mx-auto max-w-4xl px-6 pt-8 lg:px-10">
        <x-breadcrumbs :items="$breadcrumbs" />
    </div>

    <section class="relative overflow-hidden pt-8 pb-12">
        <div class="container mx-auto max-w-4xl px-6 lg:px-10">
            <x-hero-eyebrow class="mb-4">
                {{ $event->start_date->isFuture() ? 'Upcoming meetup' : 'Past meetup' }}
            </x-hero-eyebrow>

            <h1 class="mb-6 text-[clamp(2rem,4vw,3rem)] font-medium leading-tight text-mist-100">
                {{ $event->name }}
            </h1>

            <div class="mb-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-mist-300">
                <div class="inline-flex items-center gap-2">
                    <flux:icon name="calendar" variant="micro" class="size-4" />
                    {{ $event->displayPeriod() }}
                </div>

                @if ($event->location)
                    <div class="inline-flex items-center gap-2">
                        <flux:icon name="map-pin" variant="micro" class="size-4" />
                        {{ $event->location?->name }}, {{ $event->location?->city }}
                    </div>
                @endif
            </div>

            @if ($event->description)
                <div class="mb-10 text-base leading-[1.75] text-mist-200">
                    {!! nl2br(e($event->description)) !!}
                </div>
            @endif

            @if ($event->meetup_link && $event->start_date->isFuture())
                <div class="mb-12">
                    <flux:button :href="$event->meetup_link"
                                 variant="primary"
                                 target="_blank"
                                 rel="noopener"
                                 icon:trailing="arrow-up-right">
                        RSVP
                    </flux:button>
                </div>
            @endif

            @if ($event->talks->isNotEmpty())
                <div class="border-t border-mist-700 pt-10">
                    <x-section-label>Talks</x-section-label>

                    <div class="space-y-6">
                        @foreach ($event->talks as $talk)
                            <article class="rounded border border-mist-700 p-6">
                                <h3 class="mb-2 text-xl font-medium text-mist-100 hover:underline">
                                    <a href="{{ $talk->show_url }}">
                                        {{ $talk->title }}
                                    </a>
                                </h3>

                                @if ($talk->speakers->isNotEmpty())
                                    <p class="mb-3 text-sm text-mist-400">
                                        by
                                        @foreach($talk->speakers as $speaker)
                                            @if($loop->first === false && $loop->last === true)
                                                and
                                            @endif
                                            <x-sla-ui.link
                                                :href="$speaker->show_url">
                                                {{ $speaker->name }}
                                            </x-sla-ui.link>
                                            @if($loop->first === false && $loop->last === false)
                                                ,
                                            @endif
                                        @endforeach
                                    </p>
                                @endif

                                @if ($talk->description)
                                    <p class="mb-4 text-mist-300">
                                        {{ $talk->description }}
                                    </p>
                                @endif

                                @if ($talk->recording_url)
                                    <flux:button :href="$talk->recording_url"
                                                 variant="ghost"
                                                 target="_blank"
                                                 rel="noopener"
                                                 icon:trailing="arrow-up-right">
                                        Watch recording
                                    </flux:button>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($event->location && ($event->location->address || $event->location->description))
                <div class="mt-10 border-t border-mist-700 pt-10">
                    <x-section-label>Venue</x-section-label>

                    <p class="mb-1 font-medium text-mist-100">{{ $event->location->name }}</p>

                    @if ($event->location->address)
                        <p class="text-mist-300">
                            {{ $event->location->address }}<br>
                            {{ $event->location?->zip_code }} {{ $event->location?->city }}
                        </p>
                    @else
                        <p class="text-mist-300">{{ $event->location?->city }}</p>
                    @endif

                    @if ($event->location->description)
                        <p class="mt-3 text-mist-300">{{ $event->location->description }}</p>
                    @endif
                </div>
            @endif

            <div class="mt-12">
                <flux:link :href="route('events.index')">
                    ← All events
                </flux:link>
            </div>
        </div>
    </section>
</x-app-layout>
