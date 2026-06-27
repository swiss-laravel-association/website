@props([
    'platform',
    'url',
])

<a href="{{ $url }}"
   target="_blank"
   rel="noopener"
   {{ $attributes->class([
       'flex items-center gap-3 rounded-md border border-mist-700 px-3 py-1.5 text-xs text-mist-300 transition-colors hover:border-mist-500 hover:text-mist-50',
   ]) }}
>
    <span class="w-20 shrink-0 font-sans font-semibold tracking-wider text-primary-500">
        {{ $platform }}
    </span>
    {{ $slot }}
</a>
