<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guest();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email:dns', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::default()]
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => str($this->input('email'))
                ->squish()
                ->lower()
                ->value()
        ]);
    }
}
