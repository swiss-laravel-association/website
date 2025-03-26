@props([
    'name' => null,
    'url',
    'img',
    'background-color' => null,
])

<li>
    <a href="{{ $url }}"
       target="_blank"
       style="background-color: {{ $backgroundColor }};"
       class="block p-4"
    >
        <img src="{{ $img }}"
             alt="{{ $name }}"
             @class([
                'max-w-full min-w-full max-h-40',
                'dark:grayscale dark:invert' => \Illuminate\Support\Str::endsWith($img, ['.svg', '.webp']),
             ])
        >
    </a>
</li>
