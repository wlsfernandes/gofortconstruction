<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveSiteSettingsRequest extends FormRequest
{
    /**
     * Admin routes are already behind the manage-site gate; re-check here so
     * the request is safe if it is ever reused on another route.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-site') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $socialLinkRules = ['nullable', 'url:http,https', 'max:255'];

        return [
            'phone_display' => ['required', 'string', 'max:50'],
            // Digits only (plus spacing and punctuation): the tel: link is built from it.
            'phone_short' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-.\s]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'service_area' => ['required', 'string', 'max:100'],
            'service_area_tagline' => ['required', 'string', 'max:150'],
            'facebook_url' => $socialLinkRules,
            'instagram_url' => $socialLinkRules,
            'linkedin_url' => $socialLinkRules,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_short.regex' => 'Use digits only, for example 470-887-3678.',
        ];
    }
}
