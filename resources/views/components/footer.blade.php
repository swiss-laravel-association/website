
    <section class="border-y border-mauve-700 bg-mauve-950 py-10">
        <div class="container mx-auto max-w-6xl px-6 lg:px-10">
            <div class="grid items-start gap-16 md:grid-cols-2">
                <div>
                    <h2 class="mb-2 text-3xl font-light text-mauve-50">
                        Stay in the loop.
                    </h2>
                    <p class="mb-4 text-sm text-mauve-300">
                        Get notified about upcoming meetups, new talk recordings and community news.
                        No spam, ever. Unsubscribe any time.
                    </p>

                    <livewire:newsletter-signup />
                </div>

                <div id="socials">
                    <p class="mb-3 font-mono text-xs uppercase tracking-wide text-mauve-300">
                        {{ __('nav.follow_us') }}
                    </p>

                    <div class="flex flex-col gap-2">
                        <x-social-link platform="YouTube" url="{{ route('links.youtube') }}">Swiss Laravel Association</x-social-link>
                        <x-social-link platform="Mastodon" url="{{ route('links.mastodon') }}">@swiss_laravel_association@phpc.social</x-social-link>
                        <x-social-link platform="Bluesky" url="{{ route('links.bluesky') }}">@laravel.swiss</x-social-link>
                        <x-social-link platform="LinkedIn" url="{{ route('links.linkedin') }}">Swiss Laravel Association</x-social-link>
                        <x-social-link platform="Twitter/X" url="{{ route('links.x') }}">@swisslaravel</x-social-link>
                    </div>
                </div>
            </div>
        </div>
    </section>

<div
    class="relative h-20 overflow-hidden before:absolute before:inset-x-0 before:top-0 before:z-10 before:h-7 before:bg-gradient-to-b before:from-mauve-950 before:to-transparent before:content-['']"
    aria-hidden="true">
    <canvas id="footerAsciiCanvas" class="block h-20 w-full"></canvas>
</div>
<footer class="border-t-[0.5px] border-mauve-700 py-4">
    <div class="container mx-auto max-w-6xl px-6 lg:px-10">
        <div class="flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
            <span class="font-mono text-xs text-mauve-400">
                &copy; 2024 - {{ now()->year }} Swiss Laravel Association
            </span>

            <ul class="flex flex-wrap gap-6">
                <li>
                    <x-nav.link href="{{ route('imprint') }}" class="text-xs">
                        Imprint
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('privacy-policy') }}" class="text-xs">
                        Privacy Policy
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('links.feedback') }}" class="text-xs">
                        Feedback
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('links.newsletter') }}" class="text-xs">
                        Newsletter
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('links.rfp') }}" class="text-xs">
                        Request for Proposal
                    </x-nav.link>
                </li>
                <li>
                    <x-nav.link href="{{ route('meetups.calendar') }}" class="text-xs">
                        Event Calendar
                    </x-nav.link>
                </li>
            </ul>
        </div>
    </div>
</footer>
