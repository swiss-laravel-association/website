<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PrivacyPolicyController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.privacy-policy', [
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Privacy policy'],
            ],
        ]);
    }
}
