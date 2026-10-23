@props([
    'items' => [],
])

{{--
    Renders a Flux breadcrumb trail plus a matching BreadcrumbList JSON-LD block.

    Pass either an \App\Helpers\Breadcrumbs instance or a raw array of:
      - label (string, required) — visible text and schema name
      - url   (string, optional) — if present, the item is a link
      - icon  (string, optional) — if present, the visible item is icon-only
                                   (the label is still used as the schema name)
    The last item typically omits `url` to represent the current page.
--}}
@php
    $items = $items instanceof \App\Helpers\Breadcrumbs ? $items->toArray() : $items;
@endphp

@if (! empty($items))
    @php
        // The slot renders before the layout's @head directive runs, so the schema reaches the <head>.
        // The last crumb has no url, so it falls back to the current page.
        \Laravel\Head\Facades\Head::schema(
            collect($items)->reduce(
                fn ($breadcrumbs, $item) => $breadcrumbs->item($item['label'], $item['url'] ?? url()->current()),
                \Laravel\Head\Facades\Schema::breadcrumbs(),
            )
        );
    @endphp

    <flux:breadcrumbs {{ $attributes }}>
        @foreach ($items as $item)
            <flux:breadcrumbs.item
                :href="$item['url'] ?? null"
                :icon="$item['icon'] ?? null"
                :aria-label="! empty($item['icon']) ? $item['label'] : null"
            >
                @if (empty($item['icon']))
                    {{ $item['label'] }}
                @endif
            </flux:breadcrumbs.item>
        @endforeach
    </flux:breadcrumbs>
@endif
