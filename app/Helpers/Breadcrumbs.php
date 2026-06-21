<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Fluent builder for breadcrumb trails. Every instance starts with a Home crumb.
 *
 * Example:
 *   Breadcrumbs::make()
 *       ->add('Events', route('events.index'))
 *       ->add($event->name);
 *
 * @phpstan-type BreadcrumbItem array{label: string, url?: string, icon?: string}
 *
 * @implements Arrayable<int, BreadcrumbItem>
 */
class Breadcrumbs implements Arrayable
{
    /** @var list<BreadcrumbItem> */
    private array $items = [];

    public function __construct()
    {
        $this->items[] = [
            'label' => 'Home',
            'url' => route('home'),
            'icon' => 'home',
        ];
    }

    public static function make(): self
    {
        return new self;
    }

    public function add(string $label, ?string $url = null, ?string $icon = null): self
    {
        $item = ['label' => $label];

        if ($url !== null) {
            $item['url'] = $url;
        }

        if ($icon !== null) {
            $item['icon'] = $icon;
        }

        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<BreadcrumbItem>
     */
    public function toArray(): array
    {
        return $this->items;
    }
}
