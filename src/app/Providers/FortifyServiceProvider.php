<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\LoginResponse;
use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse as VerifyEmailViewResponseContract;
use App\Actions\Fortify\VerifyEmailViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 会員登録後のリダイレクト先を上書き
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);

        // CreatesNewUsers コントラクトに自作クラスをバインド
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));

        $this->app->singleton(
            VerifyEmailViewResponseContract::class,
            VerifyEmailViewResponse::class
    );
    }
}
