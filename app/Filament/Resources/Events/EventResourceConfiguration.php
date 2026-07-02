<?php

namespace App\Filament\Resources\Events;

use Filament\Resources\ResourceConfiguration;

class EventResourceConfiguration extends ResourceConfiguration
{
    protected ?string $timeframe = null;

    protected ?string $navigationLabel = null;

    protected ?int $navigationSort = null;

    public function upcoming(): static
    {
        $this->timeframe = 'upcoming';

        return $this;
    }

    public function past(): static
    {
        $this->timeframe = 'past';

        return $this;
    }

    public function getTimeframe(): ?string
    {
        return $this->timeframe;
    }

    public function navigationLabel(string $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function getNavigationLabel(): ?string
    {
        return $this->navigationLabel;
    }

    public function navigationSort(int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }
}
