<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        $planId = $this->route('plan')?->id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'alpha_dash', Rule::unique('subscription_plans', 'slug')->ignore($planId)],
            'description' => ['nullable', 'string'],
            'monthly_price' => ['nullable', 'numeric', 'min:0'],
            'yearly_price' => ['nullable', 'numeric', 'min:0'],
            'limits' => ['nullable', 'array'],
            'limits.max_projects' => ['nullable', 'integer', 'min:0'],
            'limits.max_notes' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'limits' => array_filter([
                'max_projects' => $this->input('limits.max_projects'),
                'max_notes' => $this->input('limits.max_notes'),
            ], fn ($value) => $value !== null && $value !== ''),
        ]);
    }
}
