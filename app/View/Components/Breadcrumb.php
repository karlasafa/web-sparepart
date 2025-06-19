<?php

namespace App\View\Components;

use Closure;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;

class Breadcrumb extends Component
{
    public $currentSegment;

    /**
     * Buat instance komponen baru.
     *
     * @description Konstruktor ini mengambil URL saat ini, membersihkannya,
     *              dan memecahnya menjadi segmen-segmen. Segmen saat ini
     *              disimpan dalam properti $currentSegment.
     */
    public function __construct()
    {
        $url = Request::getRequestUri();

        // Menghilangkan "/" di awal URL
        $cleanedUrl = Str::replaceFirst("/", "", $url);

        // Memecah string berdasarkan "/"
        $segments = Str::of($cleanedUrl)->explode("/");

        // Mendapatkan segmen saat ini, jika tidak ada segmen, gunakan segmen pertama
        $segment = $segments[1] ?? $segments[0];

        // Mengganti "-" dengan spasi jika ada dalam segmen
        if (Str::contains($segment, "-")) {
            $segment = Str::replace("-", " ", $segment);
        }

        // Menyimpan segmen saat ini ke dalam properti
        $this->currentSegment = $segment;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("dashboard.components.breadcrumb");
    }
}
