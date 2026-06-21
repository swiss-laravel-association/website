<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ImprintController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.imprint', [
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Imprint'],
            ],
        ]);
    }
}
