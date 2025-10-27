<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 条件データを作成（外部キー用）
        Condition::create(['id' => 1, 'name' => '良好']);
    }

    /** @test */
    public function ユーザーは商品にいいねして解除できる()
    {
        // 出品者ユーザー
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ]);

        // いいねするユーザー
        $buyer = User::create([
            'name' => 'テストユーザー',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password'),
        ]);
        $buyer->email_verified_at = now();
        $buyer->save();

        // アイテム作成（出品者が作成）
        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => 1,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'テスト用の腕時計です',
            'price' => 15000,
            'status' => 'active',
            'path' => 'items/test.jpg',
        ]);

        // --------------------
        // いいね登録
        // --------------------
        $response = $this->actingAs($buyer)
                         ->post(route('favorites.toggle', ['item_id' => $item->id]));

        $response->assertStatus(302); // back() なのでリダイレクト

        $this->assertDatabaseHas('favorites', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->fresh()->favoritedBy()->count());

        // --------------------
        // いいね解除
        // --------------------
        $response = $this->actingAs($buyer)
                         ->post(route('favorites.toggle', ['item_id' => $item->id]));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(0, $item->fresh()->favoritedBy()->count());
    }
}
