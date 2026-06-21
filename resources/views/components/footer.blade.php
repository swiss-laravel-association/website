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
                        Contact
                    </x-nav.link>
                </li>
            </ul>
        </div>
    </div>
</footer>
