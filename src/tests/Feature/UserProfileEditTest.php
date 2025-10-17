<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーで初期データ作成
        $this->artisan('migrate:fresh --seed --env=testing');
    }

    /** @test */
    public function ユーザー情報変更画面で初期値が正しく表示される()
    {
        $user = User::first();

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        // フォームの value 属性として表示されている文字列をチェック
        $response->assertSee('value="' . $user->name . '"', false);
        $response->assertSee('value="' . $user->profile->postal_code . '"', false);
        $response->assertSee('value="' . $user->profile->address . '"', false);
        $response->assertSee('value="' . $user->profile->building_name . '"', false);

        // プロフィール画像の確認
        if ($user->profile->avatar_path) {
            $response->assertSee($user->profile->avatar_path, false);
        } else {
            $response->assertSee('avatar-placeholder', false);
        }
    }

    /** @test */
    public function ログインしていない場合はプロフィール編集ページにアクセスできない()
    {
        $response = $this->get('/mypage/profile');
        $response->assertRedirect('/login');
    }
}
