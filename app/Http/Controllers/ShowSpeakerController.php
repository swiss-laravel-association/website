<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\HeadSchemas;
use App\Models\Speaker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Head;

class ShowSpeakerController extends Controller
{
    public function __invoke(Request $request, Speaker $speaker): View|RedirectResponse
    {
        if ($request->route()->originalParameter('speaker') !== $speaker->permalink()) {
            return redirect($speaker->show_url, 301);
        }

        $speaker->load(['talks']);

        Head::title($speaker->name)
            ->description(Str::limit($speaker->bio ?: sprintf('%s is a speaker at Swiss Laravel Association meetups.', $speaker->name), 160))
            ->schema(HeadSchemas::speaker($speaker));

        if ($speaker->talks->isNotEmpty()) {
            Head::schema(HeadSchemas::talkList($speaker->talks, sprintf('Talks by %s', $speaker->name)));
        }

        return view('pages.speakers.show', [
            'speaker' => $speaker,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Speakers')
                ->add($speaker->name),
        ]);
    }
}
