<x-app-layout>
    <x-sla-ui.container class="max-w-5xl">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-sla-ui.container>

    <x-sla-ui.container class="max-w-5xl space-y-24">
        <div class="max-w-3xl">
            <x-hero-eyebrow class="mb-2">Swiss Laravel Association</x-hero-eyebrow>
            <flux:heading size="xl" level="1">
                About us
            </flux:heading>

            <flux:text class="mt-4 space-y-4">
                <p>
                    We are a non-profit association that brings Laravel and PHP developers together across
                    Switzerland. Once a month we meet in a different city for two talks, drinks and snacks. Since
                    January 2025 we've held {{ $eventsCount }} events in {{ $citiesCount }} cities, from Geneva to
                    St. Gallen.
                </p>
            </flux:text>
        </div>

        <div class="max-w-3xl">
            <x-hero-eyebrow class="mb-2">What we do</x-hero-eyebrow>
            <flux:heading size="xl" level="2">
                Meetups, recordings and a community
            </flux:heading>

            <flux:text class="mt-4">
                <ul class="list-disc pl-5 space-y-2">
                    <li>
                        Monthly <flux:link :href="route('events.index')">meetups</flux:link>, hosted by local
                        companies
                    </li>
                    <li>
                        Talk recordings on <flux:link :href="route('links.youtube')">YouTube</flux:link>, so you can
                        watch them if you couldn't attend
                    </li>
                    <li>
                        A <flux:link :href="route('links.newsletter')">newsletter</flux:link> and community chats on
                        <flux:link :href="route('links.discord')">Discord</flux:link> and
                        <flux:link :href="route('links.whatsapp')">WhatsApp</flux:link>
                    </li>
                    <li>Support for members who want to learn, speak or attend conferences</li>
                </ul>
            </flux:text>
        </div>

        <div class="max-w-3xl">
            <x-hero-eyebrow class="mb-2">How we're organised</x-hero-eyebrow>
            <flux:heading size="xl" level="2">
                A volunteer-run association
            </flux:heading>

            <flux:text class="mt-4 space-y-4">
                <p>
                    We were founded on 26 September 2024 in Zürich and are registered as an association under Swiss
                    law. We're politically independent and run entirely by volunteers. Money from sponsors and members
                    goes back into the community.
                </p>
                <p class="flex flex-row gap-4 mt-4">
                    <flux:link href="https://github.com/swiss-laravel-association/policies/blob/main/statutes.md">
                        Read our statutes →
                    </flux:link>
                    <flux:link :href="route('links.code_of_conduct')">
                        Code of conduct →
                    </flux:link>
                </p>
            </flux:text>
        </div>

        <div>
            <x-hero-eyebrow class="mb-2">Who's behind it</x-hero-eyebrow>
            <flux:heading size="xl" level="2">
                Executive committee
            </flux:heading>

            <ul class="mt-12 grid grid-cols-1 min-[450px]:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach($executiveCommittee as $member)
                    <li class="flex flex-col items-center text-center">
                        <img
                            src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/team/'.$member['github'].'.webp') }}"
                            alt="{{ $member['name'] }}"
                            width="128"
                            height="128"
                            loading="lazy"
                            class="size-32 rounded-full object-cover"
                        />
                        <flux:heading level="3" class="mt-4">{{ $member['name'] }}</flux:heading>
                        <flux:text>{{ $member['role'] }}</flux:text>
                        <flux:link href="https://github.com/{{ $member['github'] }}" class="mt-1 text-sm">
                            {{ '@'.$member['github'] }}
                        </flux:link>
                    </li>
                @endforeach
            </ul>
        </div>

        <x-dither-divider canvasId="ditherDivider1" :speed="0.010" :full-bleed="true" />

        <div class="mb-12 max-w-3xl">
            <x-hero-eyebrow class="mb-2">Get involved</x-hero-eyebrow>
            <flux:heading size="xl" level="2">
                Help us grow the community
            </flux:heading>

            <flux:text class="mt-4 flex flex-col gap-1">
                <flux:link :href="route('links.rfp')">Give a talk →</flux:link>
                <flux:link href="mailto:{{ config('sla.contact.general_email') }}">Host a meetup →</flux:link>
                <flux:link :href="route('association.sponsors')">Sponsor us →</flux:link>
            </flux:text>
        </div>
    </x-sla-ui.container>
</x-app-layout>
