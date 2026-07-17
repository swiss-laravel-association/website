<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Models\Talk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShowTalkController extends Controller
{
    public function __invoke(Request $request, Talk $talk): View|RedirectResponse
    {
        if ($request->route()->originalParameter('talk') !== $talk->permalink()) {
            return redirect($talk->show_url, 301);
        }

        $talk->load(['speakers', 'events']);

        return view('pages.talks.show', [
            'talk' => $talk,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Talks')
                ->add($talk->title),
        ]);
    }
}
