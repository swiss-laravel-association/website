<?php

namespace App\Http\Controllers\Association;

use App\Helpers\Breadcrumbs;
use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class SponsorsController extends Controller
{
    public function __invoke(): View
    {
        seo(new SEOData(
            title: 'Sponsors',
            description: 'The companies and individuals supporting the Swiss Laravel Association — founding sponsors, event hosts and infrastructure partners.',
        ));

        return view('pages.association.sponsors', [
            'foundingSponsors' => Sponsor::founding()->orderBy('order')->get(),
            'locationSponsors' => Sponsor::location()->orderBy('order')->get(),
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Association')
                ->add('Sponsors'),
        ]);
    }
}
