<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use Illuminate\Contracts\View\View;

class PrivacyPolicyController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.privacy-policy', [
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Privacy policy'),
        ]);
    }
}
