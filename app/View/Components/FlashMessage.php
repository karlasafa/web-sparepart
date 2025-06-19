<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FlashMessage extends Component
{
    public $class, $index_page;

    /**
     * Create a new component instance.
     */
    public function __construct($class = null, $index_page = null)
    {
        $this->class = $class;
        $this->index_page = $index_page;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("dashboard.components.flash-message");
    }
}
