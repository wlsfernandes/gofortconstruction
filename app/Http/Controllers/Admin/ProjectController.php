<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\SaveProject;
use App\Enums\ProjectCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    private const PER_PAGE = 25;

    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::latest()->latest('id')->paginate(self::PER_PAGE),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Project);
    }

    public function store(SaveProjectRequest $request, SaveProject $saveProject): RedirectResponse
    {
        $project = $saveProject->handle(new Project, $request);

        return redirect()->route('admin.projects.index')->with('status', "Added {$project->title}.");
    }

    public function edit(Project $project): View
    {
        return $this->form($project);
    }

    public function update(SaveProjectRequest $request, Project $project, SaveProject $saveProject): RedirectResponse
    {
        $saveProject->handle($project, $request);

        return redirect()->route('admin.projects.index')->with('status', "Updated {$project->title}.");
    }

    public function destroy(Project $project, SaveProject $saveProject): RedirectResponse
    {
        $saveProject->delete($project);

        return redirect()->route('admin.projects.index')->with('status', "Deleted {$project->title}.");
    }

    private function form(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'categoryOptions' => collect(ProjectCategory::cases())
                ->mapWithKeys(fn (ProjectCategory $category): array => [$category->value => $category->label()])
                ->all(),
        ]);
    }
}
