<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ServiceArea;
use Illuminate\View\View;

class ServiceAreaController extends Controller
{
    public function __invoke(): View
    {
        return view('site.service-areas', ['serviceAreas' => ServiceArea::orderBy('city')->get()]);
    }
}
