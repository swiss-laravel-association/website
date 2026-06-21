<x-app-layout>
    <x-sla-ui.container class="max-w-5xl">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-sla-ui.container>

    <x-sla-ui.container class="max-w-5xl space-y-24">
        <div>
            <x-hero-eyebrow class="mb-2">Swiss Laravel Association</x-hero-eyebrow>
            <flux:heading size="xl" level="2">
                Founding sponsors
            </flux:heading>

            <flux:text>
                We welcome these companies as our founding sponsors. Each contributed CHF 500 or more to help us start
                the association and support the Swiss Laravel community.
            </flux:text>

            <div>
                <ul class="mt-12 grid grid-cols-1 min-[450px]:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8 items-center">
                    @foreach($foundingSponsors as $foundingSponsor)
                        <x-sponsoring.item :img="$foundingSponsor->getFirstMediaUrl('logo', 'thumb')"
                                           :url="$foundingSponsor->website"
                                           :name="$foundingSponsor->name"
                                           :background-color="$foundingSponsor->background_color"
                        />
                    @endforeach
                </ul>
            </div>

            @if (false)
                <div class="max-w-3xl mt-16 space-y-2">
                    <p class="text-xl dark:text-white">
                        Become a <span class="font-bold text-primary-700 dark:text-primary-600">Founding Sponsor</span>
                        by
                        making a one-time contribution of <span
                            class="font-bold text-primary-700 dark:text-primary-600">CHF 500 or more</span>.
                    </p>

                    <p class="text-gray-600 dark:text-gray-400">
                        For a <span class="font-bold">limited time</span> (until summer 2025) you have the opportunity
                        to
                        become a pioneering founding sponsor of the officially-recognized
                        Swiss Laravel Association. We are a non-profit association, run by members of the community and
                        registered under Swiss law. We have
                        the sole goal to grow the Swiss Laravel community and support our members in their developer
                        journey.
                    </p>

                    <h3 class="text-xl font-semibold dark:text-white">What you will get?</h3>

                    <ul class="text-gray-600 dark:text-gray-400 list-disc">
                        <li>Get your logo forever in a special founder section on the website and for 24 months on a
                            slide
                            that we show at every monthly meetup
                        </li>
                        <li>Get mentioned in an announcement on our social channels</li>
                    </ul>

                    <h3 class="text-xl font-semibold dark:text-white">How to we use your contribution?</h3>

                    <p class="text-gray-600 dark:text-gray-400">
                        All the funds will be used to operatively kickstart the associations activities and support the
                        community (e.g. meetups, member education, conference visits).
                    </p>

                    <h3 class="text-xl font-semibold dark:text-white">How can I become a founding sponsor?</h3>

                    <p class="text-gray-600 dark:text-gray-400">
                        If your company wants to be a founding sponsor, contact Sascha at <a
                            class="font-semibold hover:underline rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:focus-visible:outline-white"
                            href="mailto:sascha@laravel.swiss">sascha@laravel.swiss</a>
                    </p>
                </div>
            @endif
        </div>

        <div>
            <x-hero-eyebrow class="mb-2">
                Who makes our events possible?
            </x-hero-eyebrow>

            <flux:heading size="xl" level="2">
                Event and infrastructure sponsors
            </flux:heading>

            <flux:text>
                These companies support our meetups and infrastructure — providing venues, food and drinks, hosting,
                and more.
            </flux:text>

            <ul class="mt-12 grid grid-cols-1 min-[450px]:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8 items-center">
                @foreach($locationSponsors as $locationSponsor)
                    <x-sponsoring.item :img="$locationSponsor->getFirstMediaUrl('logo', 'thumb')"
                                       :url="$locationSponsor->website"
                                       :name="$locationSponsor->name"
                                       :background-color="$locationSponsor->background_color"
                    />
                @endforeach
            </ul>

        </div>

        <x-dither-divider canvasId="ditherDivider1" :speed="0.010" :full-bleed="true" />

        <div class="mb-12">
            <x-hero-eyebrow class="mb-2">
                Support the community
            </x-hero-eyebrow>

            <flux:heading size="xl" level="2">
                Interested in sponsoring us?
            </flux:heading>

            <flux:text class="space-y-4">
                <p>
                    We are a non-profit association run by volunteers. Sponsorships help us cover the running costs of
                    our meetups, support members through education and conference visits, and grow the Swiss Laravel
                    community.
                </p>
                <p>
                    Whether you would like to host a meetup, contribute to our events, or support the association
                    directly, we would love to hear from you. Get in touch at
                    <flux:link href="mailto:sascha@laravel.swiss">sascha@laravel.swiss</flux:link>.
                </p>
            </flux:text>
        </div>

    </x-sla-ui.container>

</x-app-layout>
