<?php

return [

    'accepted'             => ':attributeを承認してください',
    'active_url'           => ':attributeは有効なURLではありません',
    'after'                => ':attributeには:date以降の日付を指定してください',
    'alpha'                => ':attributeには文字のみ使用できます',
    'alpha_dash'           => ':attributeには英数字とダッシュ(-)、アンダースコア(_)のみ使用できます',
    'alpha_num'            => ':attributeには英数字のみ使用できます',
    'array'                => ':attributeには配列を指定してください',
    'before'               => ':attributeには:date以前の日付を指定してください',
    'between'              => [
        'numeric' => ':attributeは:min〜:maxの値を指定してください',
        'file'    => ':attributeは:min〜:max KBのファイルを指定してください',
        'string'  => ':attributeは:min〜:max文字で入力してください',
        'array'   => ':attributeは:min〜:max個の要素を含めてください',
    ],
    'boolean'              => ':attributeにはtrueまたはfalseを指定してください',
    'confirmed'            => ':attributeと確認用の値が一致しません',
    'date'                 => ':attributeは有効な日付ではありません',
    'email'                => ':attributeはメール形式で入力してください',
    'exists'               => '選択された:attributeは無効です',
    'filled'               => ':attributeは必須です',
    'image'                => ':attributeには画像ファイルを指定してください',
    'in'                   => '選択された:attributeは無効です',
    'integer'              => ':attributeは整数で入力してください',
    'max'                  => [
        'numeric' => ':attributeは:max以下で入力してください',
        'file'    => ':attributeは:max KB以下のファイルを指定してください',
        'string'  => ':attributeは:max文字以下で入力してください',
        'array'   => ':attributeは:max個以下の要素にしてください',
    ],
    'min'                  => [
        'numeric' => ':attributeは:min以上で入力してください',
        'file'    => ':attributeは:min KB以上のファイルを指定してください',
        'string'  => ':attributeは:min文字以上で入力してください',
        'array'   => ':attributeは:min個以上の要素にしてください',
    ],
    'numeric'              => ':attributeは数値で入力してください',
    'required'             => ':attributeを入力してください',
    'required_if'          => ':otherが:valueの場合、:attributeは必須です',
    'same'                 => ':attributeと一致しません',
    'string'               => ':attributeは文字列で入力してください',
    'unique'               => ':attributeはすでに存在します',
    'confirmed'            => ':attributeと一致しません',
    'password'             => 'パスワードが正しくありません',

    'attributes' => [
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => '確認用パスワード',
    ],

        'auth' => [
        'failed' => 'ログイン情報が登録されていません',
        'password' => 'パスワードが正しくありません',
        'throttle' => ':seconds 秒後に再試行してください。',
    ],

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'name' => 'お名前',
        'password_confirmation' => '確認用パスワード',
    ],
];
