<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class IndexEventsController extends Controller
{
    public function __invoke(Request $request): View
    {
        seo(new SEOData(
            title: 'Meetups',
            description: 'Browse upcoming and past Laravel Switzerland meetups — talks, venues and recordings from across the country.',
        ));

        $nextEvent = Event::query()
            ->with(['location'])
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->where('is_published', true)
            ->first();

        $upcomingEvents = Event::query()
            ->with(['location'])
            ->where('start_date', '>', now())
            ->when($nextEvent, fn ($query) => $query->whereKeyNot($nextEvent->getKey()))
            ->where('is_published', true)
            ->orderBy('start_date')
            ->paginate(perPage: 10, pageName: 'upcoming-events');

        $pastEvents = Event::query()
            ->with(['location'])
            ->where('start_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->where('is_published', true)
            ->paginate(perPage: 10, pageName: 'past-events');

        return view('pages.events.index', [
            'nextEvent' => $nextEvent,
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Events'),
        ]);
    }
}
