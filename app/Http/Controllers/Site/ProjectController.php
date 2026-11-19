<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /** Same cap the prototype used for the portfolio grid. */
    private const PORTFOLIO_LIMIT = 50;

    public function index(): View
    {
        return view('site.projects.index', [
            'projects' => Project::latest()->limit(self::PORTFOLIO_LIMIT)->get(),
        ]);
    }

    public function show(Project $project): View
    {
        return view('site.projects.show', ['project' => $project]);
    }
}
