<x-app-layout>
    <div class="container mx-auto max-w-4xl px-6 pt-8 lg:px-10">
        <x-breadcrumbs :items="$breadcrumbs" />
    </div>

    <section class="relative overflow-hidden pt-8 pb-12">
        <div class="container mx-auto max-w-4xl px-6 lg:px-10">
            <h1 class="mb-6 text-[clamp(2rem,4vw,3rem)] font-medium leading-tight text-mist-100">
                {{ $speaker->name }}
            </h1>

            @if ($speaker->bio)
                <div class="mb-10 text-base leading-[1.75] text-mist-200">
                    {!! nl2br(e($speaker->bio)) !!}
                </div>
            @endif

            @if ($speaker->talks->isNotEmpty())
                <div class="border-t border-mist-700 pt-10">
                    <x-section-label>Talks</x-section-label>

                    <ul class="space-y-2">
                        @foreach ($speaker->talks as $talk)
                            <li>
                                <flux:link :href="route('meetups.talks.show', $talk)">
                                    {{ $talk->title }}
                                </flux:link>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
