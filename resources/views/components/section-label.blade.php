{{-- Mono uppercase section label that trails off into a hairline rule. --}}
<p {{ $attributes->class([
    'mb-4 flex items-center gap-4 font-mono text-xs uppercase tracking-wider text-mauve-400',
    'after:h-px after:flex-1 after:bg-mauve-400 after:content-[""]',
]) }}>
    {{ $slot }}
</p>
