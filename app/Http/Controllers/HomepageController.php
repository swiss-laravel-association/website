<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sponsor;
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomepageController extends Controller
{
    public function __invoke(): View
    {
        seo(new SEOData(
            description: 'The Swiss Laravel Association brings PHP and Laravel developers together at monthly meetups across Switzerland.',
        ));

        return view('pages.homepage', [
            'nextEvent' => Event::query()
                ->with(['location'])
                ->where('start_date', '>', now())
                ->orderBy('start_date')
                ->first(),
            'futureEvents' => Event::query()
                ->with(['location'])
                ->where('start_date', '>', now())
                ->orderBy('start_date')
                ->limit(6)
                ->get(),
            'pastEvents' => Event::query()
                ->with(['location'])
                ->where('start_date', '<', now())
                ->orderBy('start_date', 'desc')
                ->limit(6)
                ->get(),
            'sponsors' => Sponsor::query()
                ->with(['media'])
                ->inRandomOrder()
                ->get(),
        ]);
    }
}
