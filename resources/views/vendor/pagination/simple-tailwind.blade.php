@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-mist-600 bg-white border border-mist-300 cursor-not-allowed leading-5 rounded-md dark:text-mist-300 dark:bg-mist-700 dark:border-mist-600">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-mist-800 bg-white border border-mist-300 leading-5 rounded-md hover:text-mist-700 focus:outline-none focus:ring ring-mist-300 focus:border-blue-300 active:bg-mist-100 active:text-mist-800 transition ease-in-out duration-150 dark:bg-mist-800 dark:border-mist-600 dark:text-mist-200 dark:focus:border-blue-700 dark:active:bg-mist-700 dark:active:text-mist-300 hover:bg-mist-100 dark:hover:bg-mist-900 dark:hover:text-mist-200">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-mist-800 bg-white border border-mist-300 leading-5 rounded-md hover:text-mist-700 focus:outline-none focus:ring ring-mist-300 focus:border-blue-300 active:bg-mist-100 active:text-mist-800 transition ease-in-out duration-150 dark:bg-mist-800 dark:border-mist-600 dark:text-mist-200 dark:focus:border-blue-700 dark:active:bg-mist-700 dark:active:text-mist-300 hover:bg-mist-100 dark:hover:bg-mist-900 dark:hover:text-mist-200">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-mist-600 bg-white border border-mist-300 cursor-not-allowed leading-5 rounded-md dark:text-mist-300 dark:bg-mist-700 dark:border-mist-600">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
