<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認証済みユーザーのみ許可
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],                   // 商品名
            'description' => ['required', 'string', 'max:255'],            // 商品説明
            'image' => ['required', 'image', 'mimes:jpeg,png'],            // 商品画像
            'categories' => ['required', 'array'],                         // 商品カテゴリー
            'condition_id' => ['required', 'exists:conditions,id'],        // 商品状態
            'price' => ['required', 'numeric', 'min:0'],                   // 商品価格
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名は必須です。',
            'description.required' => '商品説明は必須です。',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'image.required' => '商品画像は必須です。',
            'image.image' => '商品画像は画像ファイルである必要があります。',
            'image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください。',
            'categories.required' => '商品のカテゴリーは必須です。',
            'condition_id.required' => '商品の状態は必須です。',
            'condition_id.exists' => '選択した商品の状態は存在しません。',
            'price.required' => '商品価格は必須です。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
        ];
    }
}
