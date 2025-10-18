<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザーがログアウトできる()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログアウト後はトップページにリダイレクトされる
        $response->assertRedirect('/');

        // 認証されていないことを確認
        $this->assertGuest();
    }
}
