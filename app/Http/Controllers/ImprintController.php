<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ImprintController extends Controller
{
    public function __invoke(): View
    {
        seo(new SEOData(
            title: 'Imprint',
            description: 'Legal information and contact details for the Swiss Laravel Association.',
            robots: 'noindex, follow',
        ));

        return view('pages.imprint', [
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Imprint'),
        ]);
    }
}
