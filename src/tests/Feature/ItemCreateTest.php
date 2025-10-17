<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_item_create()
    {
        $response = $this->get(route('items.create'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_can_create_item_and_save_correctly()
    {
        // 1. ユーザー作成
        $user = User::factory()->create();

        // 2. カテゴリ作成（テストDB用に直接作る）
        $category1 = Category::create(['name' => 'ファッション']);
        $category2 = Category::create(['name' => '家電']);

        // 3. 商品状態作成（テストDB用に直接作る）
        $condition = Condition::create(['name' => '良好']);

        // 4. ユーザーでログイン
        $this->actingAs($user);

        // 5. 出品データ
        $itemData = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
            'condition_id' => $condition->id,
            'categories' => [$category1->id, $category2->id],
        ];

        // 6. POSTリクエストで出品
        $response = $this->post(route('items.store'), $itemData);
        $response->assertRedirect(); // 保存後リダイレクト想定

        // 7. DBに正しく保存されたか確認
        $this->assertDatabaseHas('items', [
            'name' => $itemData['name'],
            'brand' => $itemData['brand'],
            'description' => $itemData['description'],
            'price' => $itemData['price'],
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        // 8. pivot テーブル category_item にも紐づいているか確認
        $item = Item::where('name', $itemData['name'])->first();
        $this->assertNotNull($item);
        $this->assertTrue($item->categories->contains($category1));
        $this->assertTrue($item->categories->contains($category2));
    }
}
