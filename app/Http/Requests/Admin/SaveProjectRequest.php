<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProjectCategory;
use App\Models\Project;
use App\Services\PublicImageStorage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaveProjectRequest extends FormRequest
{
    /** Image list fields that accept several uploads and per-image removal. */
    public const IMAGE_LIST_FIELDS = ['before_images', 'after_images', 'gallery_images'];

    private const MAX_FILES_PER_LIST = 20;

    /**
     * Admin routes are already behind the manage-site gate; re-check here so
     * the request is safe if it is ever reused on another route.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-site') ?? false;
    }

    /**
     * Fill a blank slug from the title so the unique rule checks the real value.
     */
    protected function prepareForValidation(): void
    {
        if (blank($this->input('slug'))) {
            $this->merge(['slug' => Str::slug((string) $this->input('title'))]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Project|null $project */
        $project = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('projects')->ignore($project)],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::enum(ProjectCategory::class)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'completion_date' => ['nullable', 'date'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'testimonial' => ['nullable', 'string', 'max:5000'],
            'is_featured' => ['required', 'boolean'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'featured_image' => ['nullable', ...PublicImageStorage::IMAGE_RULES],
            ...$this->imageListRules(),
        ];
    }

    /**
     * @return array<string, array<mixed>>
     */
    private function imageListRules(): array
    {
        $rules = [];

        foreach (self::IMAGE_LIST_FIELDS as $field) {
            $rules[$field] = ['nullable', 'array', 'max:'.self::MAX_FILES_PER_LIST];
            $rules[$field.'.*'] = PublicImageStorage::IMAGE_RULES;
            $rules['remove_'.$field] = ['nullable', 'array'];
            $rules['remove_'.$field.'.*'] = ['string'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['slug.unique' => 'Another project already uses this slug.'];
    }
}
