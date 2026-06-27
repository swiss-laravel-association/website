<section {{ $attributes }}>
    <div class="container mx-auto max-w-6xl px-6 lg:px-10">
        <x-section-label>
            Impressions from past events
        </x-section-label>

        <figure>
            <section class="splide" aria-label="Impressions from past Laravel Switzerland meetups">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($impressions as $impression)
                            <li class="splide__slide">
                                <img
                                    src="{{ $impression }}"
                                    alt="Impression from a past Laravel Switzerland meetup"
                                    loading="lazy"
                                    class="aspect-[4/3] w-full rounded-sm object-cover shadow-lg"
                                />
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <figcaption class="mt-4 font-mono text-sm text-mist-400">
                Photos by <x-sla-ui.link href="https://denniskoch.dev?ref=laravel.swiss" target="_blank" rel="noopener">
                    Dennis Koch
                </x-sla-ui.link>
            </figcaption>
        </figure>
    </div>
</section>
