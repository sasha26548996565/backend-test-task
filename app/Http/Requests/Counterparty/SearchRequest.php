<?php

declare(strict_types=1);

namespace App\Http\Requests\Counterparty;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guest() == false;
    }

    public function rules(): array
    {
        return [
            'inn' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
