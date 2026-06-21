<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexEventsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $nextEvent = Event::query()
            ->with(['location'])
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->first();

        $upcomingEvents = Event::query()
            ->with(['location'])
            ->where('start_date', '>', now())
            ->when($nextEvent, fn ($query) => $query->whereKeyNot($nextEvent->getKey()))
            ->orderBy('start_date')
            ->get();

        $pastEvents = Event::query()
            ->with(['location'])
            ->where('start_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('pages.events.index', [
            'nextEvent' => $nextEvent,
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents,
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Events'],
            ],
        ]);
    }
}
