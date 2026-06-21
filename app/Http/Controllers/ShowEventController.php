<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShowEventController extends Controller
{
    public function __invoke(Request $request, Event $event): View
    {
        $event->load(['location', 'talks.speakers']);

        return view('pages.events.show', [
            'event' => $event,
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Events', 'url' => route('events.index')],
                ['label' => $event->name],
            ],
        ]);
    }
}
