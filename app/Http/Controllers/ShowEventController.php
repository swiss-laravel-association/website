<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\HeadSchemas;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Head;

class ShowEventController extends Controller
{
    public function __invoke(Request $request, Event $event): View|RedirectResponse
    {
        if ($request->route()->originalParameter('event') !== $event->permalink()) {
            return redirect($event->show_url, 301);
        }

        $event->load(['location', 'talks.speakers']);

        Head::title($event->name)
            ->description(Str::limit($event->description, 160))
            ->schema(HeadSchemas::event($event));

        return view('pages.events.show', [
            'event' => $event,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Events', route('events.index'))
                ->add($event->name),
        ]);
    }
}
