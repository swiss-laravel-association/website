<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Head;

class ImprintController extends Controller
{
    public function __invoke(): View
    {
        Head::title('Imprint')
            ->description('Legal information and contact details for the Swiss Laravel Association.')
            ->robots('noindex, follow');

        $content = Str::markdown(
            Blade::render(File::get(resource_path('policies/imprint.md')))
        );

        return view('pages.imprint', [
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Imprint'),
            'content' => $content,
        ]);
    }
}
