<x-app-layout>
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            <div class="mx-auto max-w-2xl">
                <h1 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl mb-4">
                    {{ $post->title }}
                </h1>

                <footer class="text-sm text-gray-700 font-semibold mb-4">
                    <time
                        class="dt-published"
                        title="{{ $post->published_at->format('Y-m-d') }}"
                        datetime="{{ $post->published_at->format('Y-m-d') }}"
                    >
                        {{ $post->published_at->format('M d, Y') }}
                    </time>

                    &bull;
                    {{ $post->getReadingTimeInMinutes() }} min  read
                </footer>


                <div class="prose prose-lg">
                    {!! $post->parsed_content !!}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
