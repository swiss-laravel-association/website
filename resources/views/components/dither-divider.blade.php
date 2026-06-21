@props([
    'canvasId',
    'height' => 28,
    'speed' => 0.01,
    'fullBleed' => false,
])

{{-- Animated Bayer-dithered orange stripe. The animation is initialised in resources/js/ascii.js. --}}
{{-- When `fullBleed` is true the canvas breaks out of its parent and spans the full viewport width. --}}
<canvas
    id="{{ $canvasId }}"
    {{ $attributes->class([
        'block',
        'w-full' => ! $fullBleed,
        'w-screen max-w-none relative left-1/2 -translate-x-1/2' => $fullBleed,
    ]) }}
    style="height: {{ $height }}px;"
    data-dither-divider
    data-dither-height="{{ $height }}"
    data-dither-speed="{{ $speed }}"
    aria-hidden="true"
></canvas>
