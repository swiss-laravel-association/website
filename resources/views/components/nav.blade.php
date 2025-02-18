<header class="sticky top-0 z-10 bg-primary-500 dark:bg-black">
    <nav x-data="{
                open: false,
             }"
         class="mx-auto container px-6 pt-2 lg:max-w-7xl lg:px-8 flex items-center justify-between space-x-8"
    >
        <a href="{{ route('home') }}"
           class="shrink-0 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
            <img src="{{ Vite::asset('resources/images/logos/swiss-laravel-association_white.webp') }}"
                 class="h-16"
                 alt="Logo Swiss Laravel Association"
            >
        </a>
        <button @click.prevent.stop="open = !open"
                class="sm:hidden p-2 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
        >
            <x-heroicon-c-bars-3 class="size-8 text-white"/>
        </button>
        <div x-show="open" class="!ml-0 fixed sm:hidden inset-0 backdrop-blur-sm"></div>
        <div
            class="hidden fixed right-2 sm:right-auto top-2 sm:top-auto max-h-[100vh-16px] sm:max-h-none overflow-y-auto sm:overflow-y-visible w-[calc(100vw-16px)] sm:w-auto max-w-sm sm:max-w-none bg-white sm:bg-transparent rounded-lg sm:rounded-none shadow-xl sm:shadow-none space-y-2 sm:space-y-0 sm:relative sm:grow sm:flex sm:justify-between p-2 sm:p-0"
            :class="{ 'block': open, 'hidden': !open }"
            @click.outside="open = false"
            @keydown.escape.window="open = false"
            x-trap.inert.noscroll.noreturn="open"
        >
            <div class="flex sm:hidden items-center justify-between">
                <img src="{{ Vite::asset('resources/images/logos/swiss-laravel-association.webp') }}"
                     class="h-12"
                     alt="Logo Swiss Laravel Association"
                >
                <button @click.prevent.stop="open = !open"
                        class="p-2 mr-1 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 dark:focus-visible:outline-gray-900"
                >
                    <x-heroicon-o-x-mark class="size-6 text-gray-500"/>
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
