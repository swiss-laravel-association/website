<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Swiss Laravel Association</title>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex flex-col min-h-screen font-sans antialiased bg-gray-100">
    <header class="sticky top-0 z-10 bg-primary-500 dark:bg-black">
        <nav x-data="{
                open: false,
             }"
             class="mx-auto container px-6 pt-2 lg:max-w-7xl lg:px-8 flex items-center justify-between space-x-8"
        >
            <a href="{{ route('home') }}" class="shrink-0 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                <img src="{{ Vite::asset('resources/images/logos/swiss-laravel-association_white.png') }}"
                     class="h-16"
                     alt="Logo Swiss Laravel Association"
                >
            </a>
            <button @click.prevent.stop="open = !open"
                    class="sm:hidden p-2 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
            >
                <x-heroicon-c-bars-3 class="size-8 text-white" />
            </button>
            <div x-show="open" class="!ml-0 fixed sm:hidden inset-0 backdrop-blur-sm"></div>
            <div class="hidden fixed right-2 sm:right-auto top-2 sm:top-auto max-h-[100vh-16px] sm:max-h-none overflow-y-auto sm:overflow-y-visible w-[calc(100vw-16px)] sm:w-auto max-w-sm sm:max-w-none bg-white sm:bg-transparent rounded-lg sm:rounded-none shadow-xl sm:shadow-none space-y-2 sm:space-y-0 sm:relative sm:grow sm:flex sm:justify-between p-2 sm:p-0"
                 :class="{ 'block': open, 'hidden': !open }"
                 @click.outside="open = false"
                 @keydown.escape.window="open = false"
                 x-trap.inert.noscroll.noreturn="open"
            >
                <div class="flex sm:hidden items-center justify-between">
                    <img src="{{ Vite::asset('resources/images/logos/swiss-laravel-association.png') }}"
                         class="h-12"
                         alt="Logo Swiss Laravel Association"
                    >
                    <button @click.prevent.stop="open = !open"
                            class="p-2 mr-1 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:focus-visible:outline-gray-900"
                    >
                        <x-heroicon-o-x-mark class="size-6 text-gray-500" />
                    </button>
                </div>
                <div class="space-y-2 sm:space-y-0 sm:flex sm:items-center sm:space-x-2">
                    <a @click="open = false"
                       href="{{ route('home') }}"
                       class="block p-4 hover:bg-primary-500/10 hover:sm:bg-white/10 dark:hover:bg-gray-700/10 text-primary-500 dark:text-gray-700 sm:text-white dark:sm:text-white font-semibold rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    >
                        {{ __('nav.home') }}
                    </a>
                    <a @click="open = false"
                       href="{{ route('home') }}#about"
                       class="block p-4 hover:bg-primary-500/10 hover:sm:bg-white/10 dark:hover:bg-gray-700/10 text-primary-500 dark:text-gray-700 sm:text-white dark:sm:text-white font-semibold rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    >
                        {{ __('nav.about') }}
                    </a>
                    <a @click="open = false"
                       href="{{ route('home') }}#events"
                       class="block p-4 hover:bg-primary-500/10 hover:sm:bg-white/10 dark:hover:bg-gray-700/10 text-primary-500 dark:text-gray-700 sm:text-white dark:sm:text-white font-semibold rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    >
                        {{ __('nav.events') }}
                    </a>
                </div>
                <div class="space-y-2 sm:flex sm:items-center sm:space-x-2">
                    <a @click="open = false"
                       href="{{ route('home') }}#socials"
                       class="block p-4 hover:bg-primary-500/10 hover:sm:bg-white/10 dark:hover:bg-gray-700/10 text-primary-500 dark:text-gray-700 sm:text-white dark:sm:text-white font-semibold rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    >
                        {{ __('nav.follow_us') }}
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <section class="bg-primary-500 dark:bg-black">
        <div class="container mx-auto flex items-center justify-center space-x-8 pt-2 pb-12 px-12">
            <img src="{{ Vite::asset('resources/images/logos/logo-simple.png') }}"
                 class="animate-scale hidden sm:block sm:size-48 md:size-64"
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
                    This is the sequel to the <strong>Laravel Switzerland Meetup</strong> currently<br>
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
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/group-selfie.jpeg') }}" class="block size-full object-cover">
                        </div>
                        <div class="-mt-8 aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/laravel-letters.jpeg') }}" class="block size-full object-cover">
                        </div>
                        <div class="aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/presentation.jpeg') }}" class="block size-full object-cover">
                        </div>
                        <div class="-mt-8 aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                            <img alt="" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/group-photo.jpeg') }}" class="block size-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="events" class="overflow-hidden bg-primary-500 dark:bg-black py-24 sm:py-32">
        <div class="mx-auto container px-6 lg:max-w-7xl lg:px-8">
            <div class="max-w-4xl">
                <p class="text-base/7 font-semibold text-primary-200 dark:text-gray-400">Events</p>
                <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-white dark:text-gray-300 sm:text-5xl">Meet the Swiss Laravel community</h1>
                <p class="mt-6 text-balance text-xl/8 text-primary-200 dark:text-gray-400">
                <ul role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    <li x-data="{ collapsed: true }">
                        <div class="relative">
                            <img class="aspect-[3/2] w-full rounded-2xl object-cover"
                                 src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/meetup/laravel-letters.jpeg') }}"
                                 alt=""
                            >
                            <div class="absolute right-2 bottom-2 rounded-lg rounded-br-2xl bg-white/90">
                            <span class="block font-semibold text-sm text-gray-900 px-2 py-1 text-right">
                                30. January 2025<br>
                                19:00<br>
                                Liip Arena, Zürich
                            </span>
                            </div>
                        </div>
                        <h3 class="mt-4 text-md/8 font-semibold tracking-tight text-white">Event Sourcing & Exclusive Intro to Laravel Cloud</h3>
                        <p class="text-base text-primary-100 dark:text-gray-400 line-clamp-1 cursor-pointer"
                           :class="{ 'line-clamp-1 cursor-pointer': collapsed }"
                           @click="collapsed = false"
                        >
                            Join us for the first Meetup of the year and take your Laravel skills to the next level.<br>
                            <br>
                            Doors open: 19:00<br>
                            First talk: 19:30<br>
                            <br>
                            Location: Liip AG, Limmatstrasse 183, 8005 Zürich<br>
                            <br>
                            <a href="https://www.meetup.com/laravel-switzerland-meetup/events/305019231/" class="underline">RSVP on Meetup.com</a>
                        </p>
                        <button x-show="collapsed"
                                @click="collapsed = !collapsed"
                                class="text-sm font-semibold text-white hover:text-primary-100 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                        >Read more</button>
                    </li>
                </ul>
                </p>
            </div>
        </div>
    </section>
    <footer class="bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 lg:px-8">
            <div class="md:grid md:grid-cols-4 md:gap-8">
                <div class="space-y-8 col-span-3">
                    <img class="h-14" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logos/swiss-laravel-association_white.png') }}" alt="Company name">
                    <p class="text-balance text-sm/6 text-gray-300">Bringing the Swiss Laravel community together.</p>
                    <div id="socials" class="flex gap-x-6">
                        <a href="https://x.com/swisslaravel" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
                            <span class="sr-only">X</span>
                            <x-simpleicon-x class="size-5" />
                        </a>
                        <a href="https://bsky.app/profile/laravel.swiss" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
                            <span class="sr-only">X</span>
                            <x-simpleicon-bluesky class="size-5" />
                        </a>
                        <a href="https://linkedin.com/company/swiss-laravel-association/" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
                            <span class="sr-only">LinkedIn</span>
                            <x-simpleicon-linkedin class="size-5" />
                        </a>
                        <a href="https://www.youtube.com/@laravel-switzerland-meetup" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
                            <span class="sr-only">Youtube</span>
                            <x-simpleicon-youtube class="size-5" />
                        </a>
                        <a href="https://www.meetup.com/laravel-switzerland-meetup/" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
                            <span class="sr-only">Meetup.com</span>
                            <x-simpleicon-meetup class="size-5" />
                        </a>
                        <a href="https://github.com/swiss-laravel-association" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
                            <span class="sr-only">Github</span>
                            <x-simpleicon-github class="size-5" />
                        </a>
                    </div>
                </div>
                <div class="mt-16 md:mt-0">
                    <div class="mt-10 md:mt-0">
                        <h3 class="text-sm/6 font-semibold text-white">Legal</h3>
                        <ul role="list" class="mt-6 space-y-2">
                            <li>
                                <a href="#" class="text-sm/6 text-gray-400 hover:text-white rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Imprint
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-sm/6 text-gray-400 hover:text-white rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Privacy Policy
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24">
                <p class="text-sm/6 text-gray-400">&copy; {{ now()->year }} Swiss Laravel Association. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @livewireScripts
    </body>
</html>
