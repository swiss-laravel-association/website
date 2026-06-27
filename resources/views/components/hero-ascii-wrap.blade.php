@props([
    'canvasId' => 'heroAsciiCanvas',
])

{{-- Decorative ASCII plasma canvas. The animation is initialised in resources/js/ascii.js. --}}
<div {{ $attributes->class([
    'relative h-[340px] overflow-hidden opacity-33',
]) }} aria-hidden="true">
    <canvas id="{{ $canvasId }}" class="block h-full w-full"></canvas>
</div>
