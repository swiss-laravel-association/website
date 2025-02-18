<?php

namespace App\View\Components\Content;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Section extends Component
{
    public function __construct(
        public ?string $keyWords,
        public string $title,
        public ?string $description = null,
        public bool $red = false,
    ) {}

    public function render(): View
    {
        return view('components.content.section');
    }
}
