<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // カテゴリ・状態作成（外部キー用）
        $this->seed(\Database\Seeders\CategorySeeder::class);

        Condition::create([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** @test */
    public function ゲストは出品ページにアクセスできない()
    {
        $response = $this->get(route('items.create'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function 商品を出品して正しく保存できる()
    {

        $this->withoutExceptionHandling();

        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $categories = Category::all()->take(2);
        $condition = Condition::first();

        $itemData = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
            'condition_id' => $condition->id,
            'categories' => $categories->pluck('id')->toArray(),
        ];

        // テスト環境では画像を送信しない
        // 本番では送信する場合に備えた条件はControllerで対応済み

        $response = $this->post(route('items.store'), $itemData);
        // リダイレクト確認
        $response->assertRedirect(route('items.index'));

        // DB保存確認
        $this->assertDatabaseHas('items', [
            'name' => $itemData['name'],
            'brand' => $itemData['brand'],
            'description' => $itemData['description'],
            'price' => $itemData['price'],
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        // pivotテーブルの紐付け確認
        $item = Item::where('name', $itemData['name'])->first();
        $this->assertNotNull($item);
        foreach ($categories as $category) {
            $this->assertTrue($item->categories->contains($category));
        }
    }
}
