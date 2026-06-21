<section {{ $attributes->class([
            'max-w-5xl mx-auto overflow-hidden py-24 sm:py-32',
         ]) }}
>
    <div class="mx-auto container px-6 lg:max-w-7xl lg:px-8">
        <div>
            @if($keyWords)
                <p @class([
                    'text-base/7 font-semibold',
                    'text-mauve-400' => $red,
                    'text-mauve-400' => !$red,
                ])>
                    {{ $keyWords }}
                </p>
            @endif
            <h1 @class([
                    'mt-2 text-pretty text-4xl font-semibold tracking-tight sm:text-5xl',
                ])>
                {{ $title }}
            </h1>
            @if($description)
                <p @class([
                    'mt-6 text-balance text-xl/8',
                ])>
                    {{ $description }}
                </p>
            @endif
            {{ $slot }}
        </div>
    </div>
</section>
