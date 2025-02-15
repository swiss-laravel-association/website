
    <footer class="bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 lg:px-8">
            <div class="md:grid md:grid-cols-4 md:gap-8">
                <div class="space-y-8 col-span-2">
                    <img class="h-14" src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logos/swiss-laravel-association_white.webp') }}" alt="Company name">
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
                        <a href="https://www.youtube.com/@swiss-laravel-association" class="text-gray-400 hover:text-gray-300 rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white" target="_blank">
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
                        <h3 class="text-sm/6 font-semibold text-white">Links</h3>
                        <ul role="list" class="mt-6 space-y-2">
                            <li>
                                <a href="{{ route('links.newsletter') }}" class="text-sm/6 text-gray-400 hover:text-white rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Newsletter
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('links.feedback') }}" class="text-sm/6 text-gray-400 hover:text-white rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Feedback
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('links.rfp') }}" class="text-sm/6 text-gray-400 hover:text-white rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Request for Proposal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('meetups.calendar') }}" class="text-sm/6 text-gray-400 hover:text-white rounded focus-visible:outline focus-visible:outline-1 focus-visible:outline-offset-2 focus-visible:outline-white">
                                    Event Calendar
                                </a>
                            </li>
                        </ul>
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
