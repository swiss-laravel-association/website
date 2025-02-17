<?php

namespace App\Http\Controllers;

class HomepageController extends Controller
{
    public function __invoke()
    {
        return view('welcome', [
            //
        ]);
    }
}
