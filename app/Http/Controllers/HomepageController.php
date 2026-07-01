<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Queries\PastEvents;
use App\Queries\UpcomingEvents;
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomepageController extends Controller
{
    public function __invoke(UpcomingEvents $upcomingEvents, PastEvents $pastEvents): View
    {
        seo(new SEOData(
            description: 'The Swiss Laravel Association brings PHP and Laravel developers together at monthly meetups across Switzerland.',
        ));

        return view('pages.homepage', [
            'nextEvent' => $upcomingEvents->query()->first(),
            'futureEvents' => $upcomingEvents->query()->limit(6)->get(),
            'pastEvents' => $pastEvents->query()->limit(6)->get(),
            'sponsors' => Sponsor::query()
                ->with(['media'])
                ->inRandomOrder()
                ->get(),
        ]);
    }
}
