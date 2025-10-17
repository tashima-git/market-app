<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 事前にユーザーを作成
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    /** @test */
    public function メールアドレスが入力されていない場合はエラーメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function パスワードが入力されていない場合はエラーメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function 登録されていない情報でログインするとエラーメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function 正しい情報を入力するとログインに成功する()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // リダイレクト先をマイページに合わせる
        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($this->user);
    }
}
