<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;

class SponsorsController extends Controller
{
    public function __invoke()
    {
        return view('pages.association.sponsors', [
            //
        ]);
    }
}
