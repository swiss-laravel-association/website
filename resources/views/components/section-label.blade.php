{{-- Mono uppercase section label that trails off into a hairline rule. --}}
{{-- Pass a `trailing` slot to render text or a link at the end of the rule. --}}
<p {{ $attributes->class([
    'mb-4 flex items-center gap-4 font-mono text-xs uppercase tracking-wider text-mauve-400',
]) }}>
    <span>{{ $slot }}</span>
    <span class="h-px flex-1 bg-mauve-400" aria-hidden="true"></span>
    @isset($trailing)
        <span>{{ $trailing }}</span>
    @endisset
</p>
