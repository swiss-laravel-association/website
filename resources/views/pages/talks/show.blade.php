<x-app-layout>
    <div class="container mx-auto max-w-4xl px-6 pt-8 lg:px-10">
        <x-breadcrumbs :items="$breadcrumbs" />
    </div>

    <section class="relative overflow-hidden pt-8 pb-12">
        <div class="container mx-auto max-w-4xl px-6 lg:px-10">
            <h1 class="mb-6 text-[clamp(2rem,4vw,3rem)] font-medium leading-tight text-mist-100">
                {{ $talk->title }}
            </h1>

            @if ($talk->speakers->isNotEmpty())
                <p class="mb-6 text-sm text-mist-400">
                    by {{ $talk->speakers->pluck('name')->join(', ', ' and ') }}
                </p>
            @endif

            @if ($talk->description)
                <div class="mb-10 text-base leading-[1.75] text-mist-200">
                    {!! nl2br(e($talk->description)) !!}
                </div>
            @endif

            @if ($talk->recording_url)
                <flux:button :href="$talk->recording_url"
                             variant="primary"
                             target="_blank"
                             rel="noopener"
                             icon:trailing="arrow-up-right">
                    Watch recording
                </flux:button>
            @endif
        </div>
    </section>
</x-app-layout>
