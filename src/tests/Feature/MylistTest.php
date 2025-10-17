<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 必要なシーダーを呼び出す
        $this->seed(\Database\Seeders\ConditionSeeder::class);
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\ItemSeeder::class);

        // テスト用ユーザーと商品取得
        $this->user = User::first();
        $allItems = Item::all();

        // ユーザーのお気に入り（1件だけでもOK）
        $this->favoritedItems = $allItems->take(3);
        foreach ($this->favoritedItems as $item) {
            $this->user->favoriteItems()->syncWithoutDetaching($item->id);
        }

        // 購入済み商品を作成して Sold に更新
        $purchasedItem = $allItems->first();
        Purchase::create([
            'item_id' => $purchasedItem->id,
            'user_id' => $this->user->id,
            'address' => '東京都千代田区1-1-1',
            'payment_method' => 'credit_card',
        ]);
        $purchasedItem->status = 'sold';
        $purchasedItem->save();
    }

    /** @test */
    public function mylist_page_displays_favorited_items()
    {
        $this->actingAs($this->user);
        $response = $this->get('/mylist');

        $response->assertStatus(200);

        // お気に入り商品が表示されることを確認
        foreach ($this->favoritedItems as $item) {
            $response->assertSee($item->name);
        }

        // 購入済み商品は "sold" 表示を確認
        $purchasedItem = $this->favoritedItems->first();
        if ($purchasedItem->status === 'sold') {
            $response->assertSee('sold');
        }
    }

    /** @test */
    public function mylist_page_does_not_display_unfavorited_items()
    {
        $this->actingAs($this->user);
        $response = $this->get('/mylist');

        $unfavoritedItems = Item::whereNotIn('id', $this->favoritedItems->pluck('id'))->get();
        foreach ($unfavoritedItems as $item) {
            $response->assertDontSee($item->name);
        }
    }

    /** @test */
    public function mylist_page_shows_nothing_for_guest()
    {
        // 未認証ユーザーでアクセス
        $response = $this->get('/mylist');

        $response->assertStatus(200);
        // お気に入り商品が表示されないことを確認
        foreach ($this->favoritedItems as $item) {
            $response->assertDontSee($item->name);
        }
        // 空メッセージなどの確認も可能
        $response->assertSee('商品はまだ登録されていません。');
    }
}
