<?php

namespace App\Http\Requests;

use App\Enums\ContactProjectType;
use App\Http\Controllers\Site\ContactSubmissionController;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactSubmissionRequest extends FormRequest
{
    /**
     * The estimate form is public; anyone may submit it.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'project_type' => ['nullable', Rule::enum(ContactProjectType::class)],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * Send the visitor back to the form itself, not the top of the page.
     */
    protected function getRedirectUrl(): string
    {
        return route('contact').'#'.ContactSubmissionController::FORM_ANCHOR;
    }
}
