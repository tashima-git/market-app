<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string'],
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法は必須です。',
            'address_id.required' => '配送先住所は必須です。',
            'address_id.exists' => '選択した配送先住所が存在しません。',
        ];
    }
}
