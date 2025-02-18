<x-app-layout>
    <section class="bg-primary-500 dark:bg-black">
        <div class="container mx-auto flex items-center justify-center space-x-8 pt-2 pb-12 px-12">
            <img src="{{ Vite::asset('resources/images/logos/logo-simple.webp') }}"
                 class="animate-scale hidden sm:block sm:size-24 md:size-32 brightness-[.9] dark:brightness-100"
                 alt="Logo simple"
            />
            <div>
                <span class="text-white font-semibold text-lg">Swiss Laravel Association</span>
                <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold text-white">Sponsorship</h1>
            </div>
        </div>
    </section>

    <x-content.section id="founding-sponsors"
                       key-words="Become a"
                       title="Founding Sponsor"
    >
        <x-slot:description>
            <span class="text-xl">
                by making a one-time contribution of <span class="font-bold text-primary-700 dark:text-primary-600">CHF 500 or more</span> and help to
                kickstart the <span class="font-bold text-primary-700 dark:text-primary-600">newly founded officially-recognized Swiss Laravel
                Association</span> and help the Laravel community reach the next level 🚀
            </span>
        </x-slot:description>
        <div class="max-w-3xl mt-4 space-y-2">
            <p class="text-gray-600 dark:text-gray-400">
                For a <span class="font-bold">limited time</span> (until summer 2025) you have the opportunity to become a pioneering founding sponsor of the officially-recognized
                Swiss Laravel Association. We are a non-profit association, run by members of the community and registered under Swiss law. We have
                the sole goal to grow the Swiss Laravel community and support our members in their developer journey.
            </p>

            <h3 class="text-xl font-semibold dark:text-white">What you will get?</h3>

            <ul class="text-gray-600 dark:text-gray-400 list-disc">
                <li>Get your logo forever in a special founder section on the website and for 24 months on a slide that we show at every monthly meetup</li>
                <li>Get mentioned in an announcement on our social channels</li>
            </ul>

            <h3 class="text-xl font-semibold dark:text-white">How to we use your contribution?</h3>

            <p class="text-gray-600 dark:text-gray-400">
                All the funds will be used to operatively kickstart the associations activities and support the community (e.g. meetups, member education, conference visits).
            </p>

            <h3 class="text-xl font-semibold dark:text-white">How can I become a founding sponsor?</h3>

            <p class="text-gray-600 dark:text-gray-400">
                If your company wants to be a founding sponsor, contact Sascha at <a class="font-semibold hover:underline rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:focus-visible:outline-white" href="mailto:sascha@laravel.swiss">sascha@laravel.swiss</a>
            </p>
        </div>
    </x-content.section>

    <x-content.section id="event-sponsors"
                       key-words="Event Sponsors"
                       title="Who makes our events possible?"
                       description="For our events when can count on the support of the following companies providing us with locations, food and drinks."
                       red
    >
        <ul class="mt-12 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-12 items-center">
            <x-sponsoring.item :img="\Illuminate\Support\Facades\Vite::asset('resources/images/sponsoring/logos/cyon.svg')"
                               url="https://cyon.ch" />
            <x-sponsoring.item :img="\Illuminate\Support\Facades\Vite::asset('resources/images/sponsoring/logos/liip.svg')"
                               url="https://liip.ch" />
            <x-sponsoring.item :img="\Illuminate\Support\Facades\Vite::asset('resources/images/sponsoring/logos/sysco.png')"
                               url="https://sysco.swiss" />
        </ul>
    </x-content.section>
</x-app-layout>
