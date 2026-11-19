<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __invoke(): View
    {
        return view('site.reviews', ['testimonials' => Testimonial::latest()->get()]);
    }
}
