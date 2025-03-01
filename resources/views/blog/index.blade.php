<x-app-layout>
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl">
                <h2 class="text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                    From the blog
                </h2>
                <p class="mt-2 text-lg/8 text-gray-600">
                    Get the latest news from the Swiss Laravel association.
                </p>
                <div class="mt-10 space-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16">

                    @if ($posts->isEmpty())
                        <p class="text-gray-600">
                            Looks like there are no posts yet. Check back later!
                        </p>
                    @endif

                    @foreach($posts as $post)

                        <article class="flex max-w-xl flex-col items-start justify-between">
                            <div class="flex items-center gap-x-4 text-xs">
                                <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="text-gray-500">
                                    {{ $post->published_at->format('M d, Y') }}
                                </time>
                            </div>
                            <div class="group relative">
                                <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        <span class="absolute inset-0"></span>
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                                    {{ $post->excerpt }}
                                </p>
                            </div>

                            @if (false)
                            <div class="relative mt-8 flex items-center gap-x-4">
                                    {{-- TODO: Use Avatar of Author here --}}
                                    <img
                                        src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                        alt="" class="size-10 rounded-full bg-gray-50">
                                <div class="text-sm/6">
                                    <p class="font-semibold text-gray-900">
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
            </div>
        </div>
    </div>


</x-app-layout>
