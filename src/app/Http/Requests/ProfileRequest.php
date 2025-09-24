<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    // 認証済みユーザーのみ許可
    public function authorize(): bool
    {
        return auth()->check();
    }

    // バリデーションルール
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string'],
            'building_name' => ['nullable', 'string'],
        ];
    }

    // バリデーションメッセージ（任意）
    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',
            'avatar.image' => 'プロフィール画像は画像ファイルである必要があります。',
            'avatar.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'address.required' => '住所は必須です。',
        ];
    }
}
