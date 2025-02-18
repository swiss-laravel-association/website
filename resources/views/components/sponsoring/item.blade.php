@props([
    'name' => null,
    'url',
    'img',
])

<li>
    <a href="{{ $url }}" target="_blank">
        <img src="{{ $img }}"
             alt="{{ $name }}"
             @class([
                'max-w-full min-w-full max-h-40',
                'dark:grayscale dark:invert' => \Illuminate\Support\Str::endsWith($img, ['.svg', '.webp']),
             ])
        >
        @if($name)
            <p>{{ $name }}</p>
        @endif
    </a>
</li>
