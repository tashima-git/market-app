<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーで初期データ作成（ユーザー・商品）
        $this->artisan('migrate:fresh --seed --env=testing');
    }

    /** @test */
    public function 登録した住所が商品購入画面に反映される()
    {
        $user = User::first();
        $item = Item::where('status', 'active')
                    ->where('user_id', '!=', $user->id)
                    ->first();

        // 送付先住所を変更（セッションに保存される前提）
        $newAddress = [
            'postal_code' => '123-4567',
            'address' => '東京都千代田区1-1-1',
            'building_name' => 'テストビル101',
        ];

        // 住所変更画面の POST（例としてセッション保存）
        $this->actingAs($user)
             ->withSession(['purchase_address' => $newAddress])
             ->post('/profile/address', $newAddress);

        // 商品購入画面を開く
        $response = $this->actingAs($user)
                         ->withSession(['purchase_address' => $newAddress])
                         ->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee($newAddress['postal_code']);
        $response->assertSee($newAddress['address']);
        $response->assertSee($newAddress['building_name']);
    }

    /** @test */
    public function 購入した商品に送付先住所が正しく紐づく()
    {
        $user = User::first();
        $item = Item::where('status', 'active')
                    ->where('user_id', '!=', $user->id)
                    ->first();

        // 新しい配送先住所
        $newAddress = [
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市中央区1-2-3',
            'building_name' => 'サンプルマンション505',
        ];

        // セッションに住所をセットして購入
        $this->actingAs($user)
             ->withSession(['purchase_address' => $newAddress])
             ->post("/purchase/{$item->id}", [
                 'payment_method' => 'card',
             ])
             ->assertRedirect();

        // purchases テーブルに正しく登録されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_postcode' => $newAddress['postal_code'],
            'sending_address' => $newAddress['address'],
            'sending_building' => $newAddress['building_name'],
        ]);

        // 商品ステータスも sold になっていることを確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
    }

    /** @test */
    public function ログインしていないユーザーは購入画面にアクセスできない()
    {
        $item = Item::where('status', 'active')->first();

        $response = $this->get("/purchase/{$item->id}");
        $response->assertRedirect('/login');
    }
}
