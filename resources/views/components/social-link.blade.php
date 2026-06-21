@props([
    'platform',
    'url',
])

<a href="{{ $url }}"
   target="_blank"
   rel="noopener"
   {{ $attributes->class([
       'flex items-center gap-3 rounded-md border border-mauve-700 px-3 py-1.5 text-xs text-mauve-300 transition-colors hover:border-mauve-500 hover:text-mauve-50',
   ]) }}
>
    <span class="w-20 shrink-0 font-mono font-semibold tracking-wider text-orange-500">
        {{ $platform }}
    </span>
    {{ $slot }}
</a>
