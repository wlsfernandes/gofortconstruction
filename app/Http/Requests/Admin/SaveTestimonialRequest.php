<?php

namespace App\Http\Requests\Admin;

use App\Enums\TestimonialSource;
use App\Services\PublicImageStorage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveTestimonialRequest extends FormRequest
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
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'project_type' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', ...PublicImageStorage::IMAGE_RULES],
            'remove_image' => ['nullable', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'source' => ['required', Rule::enum(TestimonialSource::class)],
        ];
    }
}
