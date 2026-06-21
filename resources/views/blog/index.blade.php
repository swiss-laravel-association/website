<x-app-layout>
    <x-sla-ui.container class="max-w-3xl">


        <x-breadcrumbs :items="$breadcrumbs" class="mb-8"/>

        <h2 class="text-pretty text-4xl font-semibold tracking-tight text-mauve-100 sm:text-5xl">
            From the blog
        </h2>
        <p class="mt-2 text-lg/8 text-mauve-500">
            Get the latest news from the Swiss Laravel association.
        </p>
        <div class="mt-10 space-y-16 border-t border-mauve-200 pt-10 sm:mt-16 sm:pt-16">

            @if ($posts->isEmpty())
                <p class="text-mauve-500">
                    Looks like there are no posts yet. Check back later!
                </p>
            @endif

            @foreach($posts as $post)

                <article class="flex max-w-xl flex-col items-start justify-between">
                    <div class="flex items-center gap-x-4 text-xs">
                        <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="text-mauve-300">
                            {{ $post->published_at->format('M d, Y') }}
                        </time>
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg/6 font-semibold text-mauve-200 group-hover:text-mauve-400">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm/6 text-mauve-400">
                            {{ $post->excerpt }}
                        </p>
                    </div>

                    @if (false)
                        <div class="relative mt-8 flex items-center gap-x-4">
                            {{-- TODO: Use Avatar of Author here --}}
                            <img
                                src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="" class="size-10 rounded-full bg-mauve-50">
                            <div class="text-sm/6">
                                <p class="font-semibold text-mauve-900">
                                    @foreach($post->authors as $author)
                                        {{ $author->name }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    @endif
                </article>

            @endforeach
        </div>
    </x-sla-ui.container>


</x-app-layout>
