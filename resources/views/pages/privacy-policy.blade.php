<x-app-layout>
    <x-sla-ui.container class="max-w-3xl">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-8" />

        <flux:heading size="xl" level="1">
            Privacy Policy
        </flux:heading>

        <div class="prose prose-invert max-w-none">
            {!! $content !!}
        </div>
    </x-sla-ui.container>
</x-app-layout>
