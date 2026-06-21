<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sponsor;
use Illuminate\Contracts\View\View;

class HomepageController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.homepage', [
            'nextEvent' => Event::query()
                ->with(['location'])
                ->where('start_date', '>', now())
                ->orderBy('start_date')
                ->first(),
            'pastEvents' => Event::query()
                ->with(['location'])
                ->where('start_date', '<', now())
                ->orderBy('start_date', 'desc')
                ->get(),
            'sponsors' => Sponsor::query()
                ->with(['media'])
                ->founding()
                ->limit(8)
                ->inRandomOrder()
                ->get(),
        ]);
    }
}
