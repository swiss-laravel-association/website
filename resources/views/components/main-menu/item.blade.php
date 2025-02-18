@props([
    'url',
])

<a @click="open = false"
   href="{{ $url }}"
   class="block p-4 hover:bg-primary-500/10 hover:sm:bg-white/10 dark:hover:bg-gray-700/10 text-primary-500 dark:text-gray-700 sm:text-white dark:sm:text-white font-semibold rounded focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
>
    {{ $slot }}
</a>
