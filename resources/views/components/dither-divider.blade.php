@props([
    'canvasId',
    'height' => 28,
    'speed' => 0.01,
])

{{-- Full-bleed animated Bayer-dithered orange stripe. The animation is initialised in resources/js/ascii.js. --}}
<canvas
    id="{{ $canvasId }}"
    {{ $attributes->class(['block w-full']) }}
    style="height: {{ $height }}px;"
    data-dither-divider
    data-dither-height="{{ $height }}"
    data-dither-speed="{{ $speed }}"
    aria-hidden="true"
></canvas>
