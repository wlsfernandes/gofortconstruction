<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    public function edit(): View
    {
        // config('site') already holds the saved values, or the defaults when none are saved.
        return view('admin.settings.edit', ['site' => config('site')]);
    }

    public function update(SaveSiteSettingsRequest $request): RedirectResponse
    {
        SiteSetting::updateOrCreate(['id' => SiteSetting::SINGLETON_ID], $request->validated());

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }
}
