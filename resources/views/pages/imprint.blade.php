<x-app-layout>
    <x-sla-ui.container class="max-w-3xl">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-8" />

        <flux:heading size="xl" level="1">
            Imprint
        </flux:heading>

        <div class="prose prose-invert">
            {!! $content !!}
        </div>
    </x-sla-ui.container>
</x-app-layout>
