<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceArea;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveServiceAreaRequest extends FormRequest
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
        /** @var ServiceArea|null $serviceArea */
        $serviceArea = $this->route('service_area');

        return [
            'city' => [
                'required', 'string', 'max:255',
                Rule::unique('service_areas')->where('state', $this->input('state'))->ignore($serviceArea),
            ],
            'county' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_primary' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['city.unique' => 'This city is already listed for that state.'];
    }
}
