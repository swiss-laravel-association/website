<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
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
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Events', route('events.index'))
                ->add($event->name),
        ]);
    }
}
