<?php

namespace App\Http\Requests\Admin;

use App\Enums\FaqCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveFaqRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string', 'max:5000'],
            'category' => ['required', Rule::enum(FaqCategory::class)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['required', 'boolean'],
        ];
    }
}
