<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable([
    'phone_display',
    'phone_short',
    'email',
    'service_area',
    'service_area_tagline',
    'facebook_url',
    'instagram_url',
    'linkedin_url',
])]
class SiteSetting extends Model
{
    /** The single row the admin settings page edits. */
    public const SINGLETON_ID = 1;

    private const CACHE_KEY = 'site_settings.config';

    protected static function booted(): void
    {
        static::saved(fn (): bool => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Saved settings as config('site') overrides; empty when nothing is saved yet.
     *
     * @return array<string, mixed>
     */
    public static function cachedSiteConfig(): array
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn (): array => self::find(self::SINGLETON_ID)?->toSiteConfig() ?? [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toSiteConfig(): array
    {
        return [
            'phone_display' => $this->phone_display,
            'phone_short' => $this->phone_short,
            'phone_dial' => preg_replace('/\D/', '', $this->phone_short),
            'email' => $this->email,
            'service_area' => $this->service_area,
            'service_area_tagline' => $this->service_area_tagline,
            // A blank link hides that network's icon.
            'social_links' => array_filter([
                'facebook' => $this->facebook_url,
                'instagram' => $this->instagram_url,
                'linkedin' => $this->linkedin_url,
            ]),
        ];
    }
}
