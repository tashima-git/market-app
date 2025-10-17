<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // シーダーでユーザー・条件・カテゴリ・商品を作成
        $this->artisan('migrate:fresh --seed --env=testing');
    }

    /** @test */
    /** @test */
public function ログインユーザーは商品を購入できる()
{
    $user = User::first(); // 購入するユーザー
    // 商品の出品者は別のユーザーにする
    $item = Item::where('status', 'active')
                ->where('user_id', '!=', $user->id)
                ->first();

    $response = $this->actingAs($user)
                     ->post("/purchase/{$item->id}", [
                         'payment_method' => 'card',
                     ]);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('purchases', [
        'user_id' => $user->id,
        'item_id' => $item->id,
    ]);

    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'status' => 'sold',
    ]);
}


    /** @test */
    public function 購入済み商品は商品一覧でsoldと表示される()
    {
        $user = User::first();
        $item = Item::where('status', 'active')->first();

        // 直接購入データ作成
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'sending_postcode' => $user->profile->postal_code,
            'sending_address' => $user->profile->address,
            'sending_building' => $user->profile->building_name,
        ]);

        $item->update(['status' => 'sold']);

        $response = $this->get('/');

        $response->assertSee('sold');
    }

    /** @test */
    public function 購入済み商品はプロフィールの購入履歴に表示される()
    {
        $user = User::first();
        $item = Item::where('status', 'active')->first();

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'sending_postcode' => $user->profile->postal_code,
            'sending_address' => $user->profile->address,
            'sending_building' => $user->profile->building_name,
        ]);

        $item->update(['status' => 'sold']);

        $response = $this->actingAs($user)->get('/mypage?tab=buy');

        $response->assertSee($item->name);
    }

    /** @test */
    public function ログインしていないユーザーは購入できない()
    {
        $item = Item::where('status', 'active')->first();

        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('purchases', [
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 自分の出品した商品は購入できない()
    {
        $user = User::first();
        $item = Item::where('user_id', $user->id)->first();

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'card',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
