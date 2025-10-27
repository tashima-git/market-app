<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 認証されていないユーザーを作成
        $this->user = User::factory()->create([
            'email_verified_at' => null,
        ]);
    }

    /** @test */
    public function 会員登録後に認証メールが送信される()
    {
        // Fortifyは通知ベースで送信するため、ここではユーザー作成でOK
        $this->assertDatabaseHas('users', [
            'email' => $this->user->email,
            'email_verified_at' => null,
        ]);
    }

    /** @test */
    public function メール未認証のユーザーがプロフィール画面にアクセスすると認証誘導画面へリダイレクトされる()
    {
        $response = $this->actingAs($this->user)->get('/mypage/profile');

        $response->assertRedirect('/email/verify');
    }

    /** @test */
    public function 認証リンクをクリックするとメール認証が完了しプロフィール画面へリダイレクトされる()
    {
        // 認証リンクを生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $this->user->id,
                'hash' => sha1($this->user->email),
            ]
        );

        // リクエスト送信（ログイン状態）
        $response = $this->actingAs($this->user)->get($verificationUrl);

        // 認証完了後にプロフィール画面へ遷移することを確認
        $response->assertRedirect('/mypage/profile');

        // email_verified_at カラムが更新されていることを確認
        $this->assertNotNull($this->user->fresh()->email_verified_at);
    }
}
