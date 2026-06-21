@props([
    'items' => [],
])

{{--
    Renders a Flux breadcrumb trail plus a matching BreadcrumbList JSON-LD block.

    $items is an array of:
      - label (string, required) — visible text and schema name
      - url   (string, optional) — if present, the item is a link
      - icon  (string, optional) — if present, the visible item is icon-only
                                   (the label is still used as the schema name)
    The last item typically omits `url` to represent the current page.
--}}
@if (! empty($items))
    @php
        $schemaItems = [];

        foreach ($items as $index => $item) {
            $listItem = \Spatie\SchemaOrg\Schema::listItem()
                ->position($index + 1)
                ->name($item['label']);

            if (isset($item['url'])) {
                $listItem->item($item['url']);
            }

            $schemaItems[] = $listItem;
        }
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

    {!! \Spatie\SchemaOrg\Schema::breadcrumbList()->itemListElement($schemaItems)->toScript() !!}
@endif
