<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class PrivacyPolicyController extends Controller
{
    public function __invoke(): View
    {
        seo(new SEOData(
            title: 'Privacy policy',
            description: 'How the Swiss Laravel Association handles personal data on this website.',
            robots: 'noindex, follow',
        ));

        return view('pages.privacy-policy', [
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Privacy policy'),
        ]);
    }
}
