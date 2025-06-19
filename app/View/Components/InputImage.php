<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputImage extends Component
{
    public $name, $previous;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $previous = null)
    {
        $this->name = $name;
        $this->previous = $previous;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("dashboard.components.input-image");
    }
}
