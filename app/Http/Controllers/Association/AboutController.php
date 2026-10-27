<?php

namespace App\Http\Controllers\Association;

use App\Helpers\Breadcrumbs;
use App\Http\Controllers\Controller;
use App\Queries\PastEvents;
use Illuminate\Contracts\View\View;
use Laravel\Head\Facades\Head;

class AboutController extends Controller
{
    public function __invoke(PastEvents $pastEvents): View
    {
        Head::title('About us')
            ->description('The Swiss Laravel Association is a volunteer-run non-profit that brings Laravel and PHP developers together at monthly meetups across Switzerland.');

        $events = $pastEvents->query()->get();

        return view('pages.association.about', [
            'eventsCount' => $events->count(),
            'citiesCount' => $events->pluck('location.city')->filter()->unique()->count(),
            'executiveCommittee' => config('sla.executive_committee'),
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Association')
                ->add('About'),
        ]);
    }
}
