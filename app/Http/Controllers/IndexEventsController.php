<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Queries\PastEvents;
use App\Queries\UpcomingEvents;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class IndexEventsController extends Controller
{
    public function __invoke(Request $request, UpcomingEvents $upcomingEvents, PastEvents $pastEvents): View
    {
        seo(new SEOData(
            title: 'Meetups',
            description: 'Browse upcoming and past Laravel Switzerland meetups — talks, venues and recordings from across the country.',
        ));

        $nextEvent = $upcomingEvents->query()->first();

        return view('pages.events.index', [
            'nextEvent' => $nextEvent,
            'upcomingEvents' => $upcomingEvents->query()
                ->when($nextEvent, fn ($query) => $query->whereKeyNot($nextEvent->getKey()))
                ->paginate(perPage: 10, pageName: 'upcoming-events'),
            'pastEvents' => $pastEvents->query()
                ->paginate(perPage: 10, pageName: 'past-events'),
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Events'),
        ]);
    }
}
