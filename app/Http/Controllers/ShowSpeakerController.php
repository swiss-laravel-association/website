<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Models\Speaker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShowSpeakerController extends Controller
{
    public function __invoke(Request $request, Speaker $speaker): View|RedirectResponse
    {
        if ($request->route()->originalParameter('speaker') !== $speaker->permalink()) {
            return redirect($speaker->show_url, 301);
        }

        $speaker->load(['talks']);

        return view('pages.speakers.show', [
            'speaker' => $speaker,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Speakers')
                ->add($speaker->name),
        ]);
    }
}
