<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const FEATURED_ITEM_LIMIT = 6;

    public function __invoke(): View
    {
        return view('site.home', [
            'featuredProjects' => Project::where('is_featured', true)->latest()->limit(self::FEATURED_ITEM_LIMIT)->get(),
            'featuredTestimonials' => Testimonial::where('is_featured', true)->latest()->limit(self::FEATURED_ITEM_LIMIT)->get(),
        ]);
    }
}
