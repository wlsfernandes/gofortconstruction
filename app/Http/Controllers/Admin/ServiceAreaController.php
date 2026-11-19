<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveServiceAreaRequest;
use App\Models\ServiceArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceAreaController extends Controller
{
    private const PER_PAGE = 25;

    public function index(): View
    {
        return view('admin.service-areas.index', [
            'serviceAreas' => ServiceArea::orderBy('city')->paginate(self::PER_PAGE),
        ]);
    }

    public function create(): View
    {
        return view('admin.service-areas.form', ['serviceArea' => new ServiceArea(['state' => 'Georgia'])]);
    }

    public function store(SaveServiceAreaRequest $request): RedirectResponse
    {
        $serviceArea = ServiceArea::create($request->validated());

        return redirect()->route('admin.service-areas.index')->with('status', "Added {$serviceArea->city}.");
    }

    public function edit(ServiceArea $serviceArea): View
    {
        return view('admin.service-areas.form', ['serviceArea' => $serviceArea]);
    }

    public function update(SaveServiceAreaRequest $request, ServiceArea $serviceArea): RedirectResponse
    {
        $serviceArea->update($request->validated());

        return redirect()->route('admin.service-areas.index')->with('status', "Updated {$serviceArea->city}.");
    }

    public function destroy(ServiceArea $serviceArea): RedirectResponse
    {
        $serviceArea->delete();

        return redirect()->route('admin.service-areas.index')->with('status', "Deleted {$serviceArea->city}.");
    }
}
