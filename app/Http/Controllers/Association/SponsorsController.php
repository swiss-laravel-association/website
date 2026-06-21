<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Contracts\View\View;

class SponsorsController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.association.sponsors', [
            'foundingSponsors' => Sponsor::founding()->orderBy('order')->get(),
            'locationSponsors' => Sponsor::location()->orderBy('order')->get(),
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Association'],
                ['label' => 'Sponsors'],
            ],
        ]);
    }
}
