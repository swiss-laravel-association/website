<?php

namespace App\Http\Controllers;

use App\Helpers\HeadSchemas;
use App\Models\Sponsor;
use App\Queries\PastEvents;
use App\Queries\UpcomingEvents;
use Illuminate\Contracts\View\View;
use Laravel\Head\Facades\Head;

class HomepageController extends Controller
{
    public function __invoke(UpcomingEvents $upcomingEvents, PastEvents $pastEvents): View
    {
        $futureEvents = $upcomingEvents->query()->limit(6)->get();

        Head::title('Swiss Laravel Association | Laravel Meetups in Switzerland', exact: true)
            ->description('The Swiss Laravel Association brings PHP and Laravel developers together at monthly meetups across Switzerland.');

        if ($futureEvents->isNotEmpty()) {
            Head::schema(HeadSchemas::eventList($futureEvents, 'Upcoming meetups'));
        }

        return view('pages.homepage', [
            'nextEvent' => $futureEvents->first(),
            'futureEvents' => $futureEvents,
            'pastEvents' => $pastEvents->query()->limit(6)->get(),
            'sponsors' => Sponsor::query()
                ->with(['media'])
                ->inRandomOrder()
                ->get(),
        ]);
    }
}
