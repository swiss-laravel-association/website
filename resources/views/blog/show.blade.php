<x-app-layout>

    <x-sla-ui.container class="max-w-3xl">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-8"/>

        <h1 class="text-pretty text-4xl font-semibold tracking-tight text-mist-200 sm:text-5xl mb-4">
            {{ $post->title }}
        </h1>

        <footer class="text-sm text-mist-400 font-semibold mb-4">
            <time
                class="dt-published"
                title="{{ $post->published_at->format('Y-m-d') }}"
                datetime="{{ $post->published_at->format('Y-m-d') }}"
            >
                {{ $post->published_at->format('M d, Y') }}
            </time>

            &bull;
            {{ $post->getReadingTimeInMinutes() }} min read
        </footer>


        <div class="prose prose-lg prose-invert">
            {!! $post->parsed_content !!}
        </div>

    </x-sla-ui.container>
</x-app-layout>
