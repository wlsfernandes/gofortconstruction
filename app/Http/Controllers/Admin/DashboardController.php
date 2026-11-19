<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactStatus;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const RECENT_REQUEST_LIMIT = 5;

    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'newRequestCount' => ContactSubmission::where('status', ContactStatus::New)->count(),
            'projectCount' => Project::count(),
            'publishedPostCount' => BlogPost::where('is_published', true)->count(),
            'testimonialCount' => Testimonial::count(),
            'recentNewRequests' => ContactSubmission::where('status', ContactStatus::New)
                ->latest()
                ->limit(self::RECENT_REQUEST_LIMIT)
                ->get(),
        ]);
    }
}
