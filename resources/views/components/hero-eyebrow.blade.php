<p {{ $attributes->class([
    'flex items-center gap-2.5 font-mono font-medium text-xs uppercase tracking-wider text-orange-500',
]) }}>
    <span aria-hidden="true" class="inline-block h-px w-6 shrink-0 bg-orange-500"></span>
    {{ $slot }}
</p>
