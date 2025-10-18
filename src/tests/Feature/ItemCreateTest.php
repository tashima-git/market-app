<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ゲストは出品ページにアクセスできない()
    {
        $response = $this->get(route('items.create'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function 商品を出品して正しく保存できる()
    {
        // 1. Storage 偽装（画像アップロード用）
        Storage::fake('public');

        // 2. ユーザー作成
        $user = User::factory()->create();

        // 3. Seederを呼んでカテゴリ作成
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $categories = Category::all()->take(2);

        // SQLite用に timestamps 明示
        foreach ($categories as $category) {
            $category->timestamps = true;
            $category->save();
        }

        // 4. 商品状態作成（timestamps 明示）
        $condition = Condition::create([
            'name' => '良好',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. ユーザーでログイン
        $this->actingAs($user);

        // 6. 出品データ
        $itemData = [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'price' => 5000,
            'condition_id' => $condition->id,
            'categories' => $categories->pluck('id')->toArray(),
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        // 7. POSTリクエストで出品
        $response = $this->post(route('items.store'), $itemData);
        $response->assertRedirect(route('items.index')); // 保存後のリダイレクト先を明示

        // 8. DBに正しく保存されたか確認
        $this->assertDatabaseHas('items', [
            'name' => $itemData['name'],
            'brand' => $itemData['brand'],
            'description' => $itemData['description'],
            'price' => $itemData['price'],
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        // 9. pivot テーブル category_item にも紐づいているか確認
        $item = Item::where('name', $itemData['name'])->first();
        $this->assertNotNull($item);
        foreach ($categories as $category) {
            $this->assertTrue($item->categories->contains($category));
        }
    }
}
