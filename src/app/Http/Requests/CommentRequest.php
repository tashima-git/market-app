<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'コメントは必須です。',
            'comment.max' => 'コメントは255文字以内で入力してください。',
        ];
    }
}
