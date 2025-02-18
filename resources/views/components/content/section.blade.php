<section {{ $attributes->class([
            'overflow-hidden py-24 sm:py-32',
            'bg-primary-500 dark:bg-black' => $red,
            'bg-white dark:bg-gray-900' => !$red,
         ]) }}
>
    <div class="mx-auto container px-6 lg:max-w-7xl lg:px-8">
        <div>
            @if($keyWords)
                <p @class([
                    'text-base/7 font-semibold',
                    'text-primary-200 dark:text-gray-400' => $red,
                    'text-gray-400' => !$red,
                ])>
                    {{ $keyWords }}
                </p>
            @endif
            <h1 @class([
                    'mt-2 text-pretty text-4xl font-semibold tracking-tight sm:text-5xl',
                    'text-white dark:text-gray-300' => $red,
                    'text-gray-900 dark:text-gray-300' => !$red,
                ])>
                {{ $title }}
            </h1>
            @if($description)
                <p @class([
                    'mt-6 text-balance text-xl/8',
                    'text-primary-200 dark:text-gray-400' => $red,
                    'text-gray-700 dark:text-gray-400' => !$red,
                ])>
                    {{ $description }}
                </p>
            @endif
            {{ $slot }}
        </div>
    </div>
</section>
