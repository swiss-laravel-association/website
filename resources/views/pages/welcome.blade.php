<x-app-layout>
    <section class="bg-primary-500 dark:bg-black">
        <div class="container mx-auto flex items-center justify-center space-x-8 pt-2 pb-12 px-12">
            <img src="{{ Vite::asset('resources/images/logos/logo-simple.webp') }}"
                 class="animate-scale hidden sm:block sm:size-48 md:size-64 brightness-[.9] dark:brightness-100"
                 alt="Logo simple"
            />
            <div>
                <span class="text-white font-semibold text-lg">Welcome to the</span>
                <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold text-white">Swiss<br>Laravel<br>Association</h1>
            </div>
        </div>
    </section>
    <section id="about" class="overflow-hidden bg-white dark:bg-gray-900 py-12 sm:py-24">
        <div class="mx-auto container px-6 lg:max-w-7xl lg:px-8">
            <div class="max-w-4xl">
                <p class="text-base/7 font-semibold text-gray-400">About us</p>
                <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 dark:text-gray-300 sm:text-5xl">Spread the word about Laravel</h1>
                <p class="mt-6 text-balance text-xl/8 text-gray-700 dark:text-gray-400">
                    Starting in 2025 the <strong>Swiss Laravel Association</strong> will organize Laravel Meetups all around Switzerland.<br>
                    This is the sequel to the <strong>Laravel Switzerland Meetup</strong> previously<br>
                    organized by <a class="font-semibold hover:underline rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:focus-visible:outline-white" href="https://x.com/ruslansteiger">Ruslan Steiger</a>.
                </p>
            </div>
            <div class="mt-20 grid grid-cols-1 lg:grid-cols-2 lg:gap-x-8 lg:gap-y-16">
                <div class="lg:pr-8">
                    <h2 class="text-pretty text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-300">Our mission</h2>
                    <p class="mt-6 text-base/7 text-gray-600">Bringing the Swiss Laravel community together on a regular base by organizing a monthly Meetup covering all topics around the Laravel and PHP ecosystem.</p>
                    <h2 class="mt-10 text-pretty text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-300">Why an association?</h2>
                    <p class="mt-6 text-base/7 text-gray-600">
                        Previously all events were organized by a single person.<br>
                        Beginning in 2025 we want to spread the workload over more shoulders and create a sustainable organization for the next years to come.
                    </p>
                    <h2 class="mt-10 text-pretty text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-300">Keep in touch</h2>
                    <p class="mt-6 text-base/7 text-gray-600">Signup to our newsletter and be the first to know about upcoming events.</pv>
                    <livewire:newsletter-signup />
                </div>
                <div class="pt-16 lg:row-span-2 lg:-mr-16 xl:mr-auto">
                    <div class="-mx-12 grid grid-cols-2 gap-4 sm:-mx-16 sm:grid-cols-4 lg:mx-0 lg:grid-cols-2 lg:gap-4 xl:gap-8">
                        <div class="aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/group-selfie.webp') }}" class="block size-full object-cover" loading="lazy">
                        </div>
                        <div class="-mt-8 aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/laravel-letters.webp') }}" class="block size-full object-cover" loading="lazy">
                        </div>
                        <div class="aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/presentation.webp') }}" class="block size-full object-cover" loading="lazy">
                        </div>
                        <div class="-mt-8 aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/group-photo.webp') }}" class="block size-full object-cover" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-content.section id="events"
                       key-words="Next Events"
                       title="Meet the Swiss Laravel community"
                       red
    >
        <p class="mt-6 text-balance text-xl/8 text-primary-200 dark:text-gray-400">
        <ul role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            @foreach(\App\Models\Event::query()->where('is_published', true)->where('end_date', '>', now()->addHours(2))->orderBy('start_date')->get() as $event)
                <li x-data="{ collapsed: true }" wire:key="{{ $event->id }}">
                    <div class="relative">
                        <img class="aspect-[3/2] w-full rounded-2xl object-cover"
                             src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/laravel-letters.webp') }}"
                             alt=""
                             loading="lazy"
                        >
                        <div class="absolute right-2 bottom-2 rounded-lg rounded-br-2xl bg-white/90">
                    <span class="block font-semibold text-sm text-gray-900 px-2 py-1 text-right">
                        {{ $event->start_date->format('d. F Y') }}<br>
                        {{ $event->start_date->format('H:i') }}<br>
                        {{ $event->location ? $event->location->name . ', ' . $event->location->city : '' }}
                    </span>
                        </div>
                    </div>
                    <h3 class="mt-4 text-md/8 font-semibold tracking-tight text-white">
                        {{ $event->name }}
                    </h3>
                    <p class="text-base text-primary-100 dark:text-gray-400 line-clamp-1 cursor-pointer"
                       :class="{ 'line-clamp-1 cursor-pointer': collapsed }"
                       @click="collapsed = false"
                    >
                        {!! nl2br($event->description) !!}
                        <br>
                        @if($event->meetup_link)
                            <a href="{{ $event->meetup_link }}" class="underline" target="_blank">RSVP on Meetup.com</a>
                        @endif
                    </p>
                    @if(\Illuminate\Support\Str::length($event->description) > 10)
                        <button x-show="collapsed"
                                @click="collapsed = !collapsed"
                                class="text-sm font-semibold text-white hover:text-primary-100 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                        >Read more</button>
                    @endif
                </li>
            @endforeach
        </ul>
        </p>
    </x-content.section>
</x-app-layout>
