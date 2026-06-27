@props([
    'href'
])

<flux:link :href="$href" variant="ghost" {{ $attributes }}>
     {{ $slot }}
</flux:link>
