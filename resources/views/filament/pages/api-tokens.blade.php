<x-filament-panels::page>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Point your MCP client at <code class="font-mono">{{ $this->getMcpUrl() }}</code> and send the token as a Bearer token.
    </p>

    @if ($newToken)
        <x-filament::section>
            <x-slot name="heading">Your new token</x-slot>
            <x-slot name="description">Copy it now. It is not shown again.</x-slot>

            <div
                x-data="{ copied: false, token: @js($newToken) }"
                class="flex flex-col gap-3"
            >
                <code class="block break-all rounded-lg bg-gray-100 p-3 font-mono text-sm dark:bg-white/5" data-test="new-token">{{ $newToken }}</code>
                <div class="flex gap-2">
                    <x-filament::button
                        size="sm"
                        x-on:click="navigator.clipboard.writeText(token).then(() => copied = true)"
                    >
                        <span x-text="copied ? 'Copied' : 'Copy'"></span>
                    </x-filament::button>
                    <x-filament::button size="sm" color="gray" wire:click="dismissNewToken">
                        Done
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    @endif

    {{ $this->table }}
</x-filament-panels::page>
