<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string'],
            'building_name' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'address.required' => '住所は必須です。',
        ];
    }
}
