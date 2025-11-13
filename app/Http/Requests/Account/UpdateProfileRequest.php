<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $this->user()->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'timezone' => ['nullable', 'timezone'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
