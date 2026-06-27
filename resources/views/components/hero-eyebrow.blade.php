<p {{ $attributes->class([
    'flex items-center gap-2.5 font-mono font-medium text-xs uppercase tracking-wider text-brand subpixel-antialiased',
]) }}>
    <span aria-hidden="true" class="inline-block h-px w-6 shrink-0 bg-brand"></span>
    {{ $slot }}
</p>
