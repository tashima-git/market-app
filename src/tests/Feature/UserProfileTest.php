<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーで初期データ作成（ユーザー・商品）
        $this->artisan('migrate:fresh --seed --env=testing');

        $this->user = User::factory()->create([
        'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function プロフィールページでユーザー情報と商品一覧が表示される()
    {
        $user = User::first();

        // 出品商品と購入済み商品を取得
        $itemForSale = Item::where('user_id', $user->id)->first();
        $purchasedItem = Purchase::where('user_id', $user->id)
                                 ->with('item')
                                 ->first()
                                 ?->item;

        // プロフィールページにアクセス（出品商品タブ）
        $response = $this->actingAs($user)->get('/mypage?tab=sell');
        $response->assertStatus(200);

        // ユーザー名とプロフィール画像の確認
        $response->assertSee($user->name);
        $response->assertSee('placeholder-avatar'); // デフォルトのavatarクラス

        // 出品商品が表示されることを確認
        if ($itemForSale) {
            $response->assertSee($itemForSale->name);
        }

        // 購入済み商品タブに切り替え
        $response = $this->actingAs($user)->get('/mypage?tab=buy');
        $response->assertStatus(200);

        // 購入済み商品が表示されることを確認
        if ($purchasedItem) {
            $response->assertSee($purchasedItem->name);
        }
    }

    /** @test */
    public function ログインしていない場合はプロフィールページにアクセスできない()
    {
        $response = $this->get('/mypage');
        $response->assertRedirect('/login');
    }
}
