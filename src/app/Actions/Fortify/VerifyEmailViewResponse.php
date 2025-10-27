<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\View;

class VerifyEmailViewResponse implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        return response()->view('auth.verify-email'); // View をレスポンスにラップ
    }
}
