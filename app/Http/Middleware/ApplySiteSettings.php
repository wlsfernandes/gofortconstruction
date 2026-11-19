<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Overlays the contact details saved in the admin area onto config('site'),
 * so views keep reading config('site.*') unchanged.
 */
class ApplySiteSettings
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach (SiteSetting::cachedSiteConfig() as $key => $value) {
            config()->set("site.{$key}", $value);
        }

        return $next($request);
    }
}
