
    <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 flex items-center">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:brand :href="route('home')" logo="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logos/logo-mark-color-small.png') }}" name="Swiss Laravel Association" class="max-lg:hidden dark:hidden" />
        <flux:brand :href="route('home')" logo="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logos/logo-mark-color-small.png') }}" name="Swiss Laravel Association" class="max-lg:hidden! hidden dark:flex" />

        <flux:spacer />

        @if (true)
            <flux:navbar class="me-4 lg:hidden">
                <flux:navbar.item :href="route('events.next-event')" icon:trailing="arrow-up-right">
                    Next Event
                </flux:navbar.item>
            </flux:navbar>
        @endif

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item href="#" current>Events</flux:navbar.item>
            <flux:navbar.item href="#">Association</flux:navbar.item>

            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon:trailing="chevron-down">Association</flux:navbar.item>

                <flux:navmenu>
                    <flux:navmenu.item href="#">About</flux:navmenu.item>
                    <flux:navmenu.item href="#">Team</flux:navmenu.item>
                    <flux:navmenu.item href="#">Membership</flux:navmenu.item>
                    <flux:navmenu.item href="#">Become a member</flux:navmenu.item>
                    <flux:navmenu.separator />
                    <flux:navmenu.item href="#">Sponsors</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
        </flux:navbar>
    </flux:header>

    <flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="#"
                logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Swiss Laravel Association"
            />

            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="#" current>Home</flux:sidebar.item>
            <flux:sidebar.item icon="inbox" badge="12" href="#">Inbox</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>

            <flux:sidebar.group expandable heading="Favorites" class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>





    @if (false)
{{-- Sticky, translucent top navigation. --}}
<header class="sticky top-0 z-50 border-b-[0.5px] border-mauve-dark-6 bg-mauve-950/50 backdrop-blur-md">
    <nav x-data="{ open: false }"
         class="container mx-auto max-w-6xl px-6 lg:px-10"
    >
        <div class="flex h-14 items-center justify-between">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500">
                <span class="flex size-8 shrink-0 items-center justify-center rounded-sm bg-orange-500" aria-hidden="true">
                    <span class="font-mono text-xs font-medium tracking-[0.02em] text-white">SLA</span>
                </span>
                <span class="font-mono text-sm text-mauve-50">Swiss Laravel Association</span>
            </a>

<flux:navbar>
    <flux:navbar.item href="#">Events</flux:navbar.item>
    <flux:navbar.item href="#">Transactions</flux:navbar.item>

    <flux:dropdown>
        <flux:navbar.item icon:trailing="chevron-down">Association</flux:navbar.item>

        <flux:navmenu>
            <flux:navmenu.item href="#">About</flux:navmenu.item>
            <flux:navmenu.item href="#">Membership</flux:navmenu.item>
            <flux:navmenu.item href="#">Become a Member</flux:navmenu.item>
            <flux:navmenu.separator />
            <flux:navmenu.item href="#">Sponsors</flux:navmenu.item>
            <flux:navmenu.item href="#">Leadership</flux:navmenu.item>
        </flux:navmenu>
    </flux:dropdown>
</flux:navbar>

            {{-- Desktop nav --}}
            <ul class="hidden items-center gap-10 sm:flex">
                <li>
                    <x-nav.link href="{{ route('home') }}#about">
                        Events
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('home') }}#about">
                        Sponsors
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('home') }}#about">
                        About Us
                    </x-nav.link>
                </li>
            </ul>

            {{-- Mobile menu trigger --}}
            <button @click.prevent.stop="open = !open"
                    class="rounded p-2 text-mauve-dark-11 sm:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500"
                    aria-label="Open menu"
            >
                <x-heroicon-c-bars-3 class="size-6" />
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open"
             x-cloak
             @click.outside="open = false"
             @keydown.escape.window="open = false"
             class="sm:hidden border-t-[0.5px] border-mauve-dark-6 py-3"
        >
            <ul class="flex flex-col gap-1">
                <li>
                    <a href="{{ route('home') }}#about" @click="open = false" class="block rounded px-2 py-2 text-sm text-mauve-dark-11 hover:bg-mauve-dark-3 hover:text-mauve-dark-12">
                        {{ __('nav.about') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}#events" @click="open = false" class="block rounded px-2 py-2 text-sm text-mauve-dark-11 hover:bg-mauve-dark-3 hover:text-mauve-dark-12">
                        {{ __('nav.events') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('association.sponsors') }}" @click="open = false" class="block rounded px-2 py-2 text-sm text-mauve-dark-11 hover:bg-mauve-dark-3 hover:text-mauve-dark-12">
                        {{ __('nav.sponsors') }}
                    </a>
                </li>
                <li>
                    <a href="https://luma.com/laravel.swiss" target="_blank" rel="noopener" class="block rounded px-2 py-2 font-mono text-sm text-orange-500">
                        &rarr; Register
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
@endif
