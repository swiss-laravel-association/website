<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Component;

class MeetupImpressions extends Component
{
    /**
     * @var Collection<int, string>
     */
    public Collection $impressions;

    public function __construct()
    {
        $this->impressions = Collection::times(
            31,
            fn (int $i): string => Vite::asset("resources/images/meetup/meetup-{$i}.webp"),
        )->shuffle();
    }

    public function render(): View
    {
        return view('components.meetup-impressions');
    }
}
