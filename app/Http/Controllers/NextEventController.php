<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NextEventController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $nextEvent = Event::where('start_date', '>', now())->orderBy('start_date', 'asc')->first();

        if ($nextEvent === null) {
            return redirect()->route('home');
        }

        return redirect($nextEvent->show_url);
    }
}
