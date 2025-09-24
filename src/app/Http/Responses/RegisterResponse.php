<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 登録後にログイン状態を維持したままプロフィール編集画面へ
        return redirect()->route('mypage.profile.edit');
    }
}
