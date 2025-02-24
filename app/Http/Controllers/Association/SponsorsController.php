<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class SponsorsController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.association.sponsors', [
            //
        ]);
    }
}
