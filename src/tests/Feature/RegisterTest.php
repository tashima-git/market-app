<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が入力されていない場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function メールアドレスが入力されていない場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが入力されていない場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワードが7文字以下の場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワード確認が一致しない場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 正しい情報を入力すると登録に成功する()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録後にプロフィールページへリダイレクトされる
        $response->assertRedirect('/mypage/profile');

        // DBにユーザーが存在することを確認
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }
}
