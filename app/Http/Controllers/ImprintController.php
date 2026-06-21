<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use Illuminate\Contracts\View\View;

class ImprintController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.imprint', [
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Imprint'),
        ]);
    }
}
