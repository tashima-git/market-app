<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class PaymentMethodSelectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーで初期データ作成（ユーザー・商品）
        $this->artisan('migrate:fresh --seed --env=testing');
    }

    /** @test */
    public function 支払い方法選択画面で選択した支払い方法が小計画面に反映される()
    {
        $user = User::first();
        $item = Item::where('status', 'active')
                    ->where('user_id', '!=', $user->id)
                    ->first();

        // 小計画面（購入確認画面）を開く
        $response = $this->actingAs($user)
                         ->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('支払い方法'); // プルダウンがあることを確認

        // 支払い方法を選択してセッションに保存する
        $response = $this->actingAs($user)
                         ->post("/purchase/{$item->id}", [
                             'payment_method' => 'konbini', // コンビニ払いを選択
                         ]);

        // リダイレクトで戻ることを確認
        $response->assertRedirect();

        // セッションに選択が保存されていることを確認
        $this->assertEquals('konbini', session('purchase_payment_method'));

        // 再度小計画面を開いたときに選択が反映される
        $response = $this->actingAs($user)
                         ->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('コンビニ払い'); // ビューに選択された支払い方法が表示される
    }
}
