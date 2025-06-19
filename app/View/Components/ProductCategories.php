<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

class ProductCategories extends Component
{
    public $categories, $urlCategory = null;
    /**
     * Create a new component instance.
     */
    public function __construct($data)
    {
        $this->categories = $data;

        if(Request::is("category/*")) {
            $this->urlCategory = Str::of(Request::getRequestUri())->explode("/")[2];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('customer.components.product-categories');
    }
}
