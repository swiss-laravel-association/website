<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\HeadSchemas;
use App\Models\Talk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Head;

class ShowTalkController extends Controller
{
    public function __invoke(Request $request, Talk $talk): View|RedirectResponse
    {
        if ($request->route()->originalParameter('talk') !== $talk->permalink()) {
            return redirect($talk->show_url, 301);
        }

        $talk->load(['speakers', 'events']);

        Head::title($talk->title)
            ->description(Str::limit($talk->description, 160))
            ->schema(HeadSchemas::talk($talk));

        return view('pages.talks.show', [
            'talk' => $talk,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Talks')
                ->add($talk->title),
        ]);
    }
}
