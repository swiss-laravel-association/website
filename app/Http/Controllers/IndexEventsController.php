<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\HeadSchemas;
use App\Queries\PastEvents;
use App\Queries\UpcomingEvents;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laravel\Head\Facades\Head;

class IndexEventsController extends Controller
{
    public function __invoke(Request $request, UpcomingEvents $upcomingEvents, PastEvents $pastEvents): View
    {
        $nextEvent = $upcomingEvents->query()->first();

        $upcoming = $upcomingEvents->query()
            ->when($nextEvent, fn ($query) => $query->whereKeyNot($nextEvent->getKey()))
            ->paginate(perPage: 10, pageName: 'upcoming-events');

        $past = $pastEvents->query()
            ->paginate(perPage: 10, pageName: 'past-events');

        Head::title('Meetups')
            ->description('Browse upcoming and past Laravel Switzerland meetups — talks, venues and recordings from across the country.');

        if ($nextEvent || $upcoming->isNotEmpty()) {
            Head::schema(HeadSchemas::eventList(
                collect([$nextEvent])->filter()->concat($upcoming->items()),
                'Upcoming meetups',
            ));
        }

        if ($past->isNotEmpty()) {
            Head::schema(HeadSchemas::eventList($past->items(), 'Past meetups'));
        }

        return view('pages.events.index', [
            'nextEvent' => $nextEvent,
            'upcomingEvents' => $upcoming,
            'pastEvents' => $past,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Events'),
        ]);
    }
}
