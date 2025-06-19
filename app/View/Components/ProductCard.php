<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $products = [];

    /**
     * Create a new component instance.
     */
    public function __construct($data)
    {
        $this->products = $data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("customer.components.product-card");
    }
}
