<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomepageController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.welcome', [
            //
        ]);
    }
}
