<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Category;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeder を呼び出す
        $this->seed(\Database\Seeders\ConditionSeeder::class);
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\ItemSeeder::class);

        // 購入済み商品を作成（ステータスも更新）
        $buyer = User::first();
        $itemToPurchase = Item::first();
        Purchase::create([
            'item_id' => $itemToPurchase->id,
            'user_id' => $buyer->id,
            'address' => '東京都千代田区1-1-1',
            'payment_method' => 'credit_card',
        ]);
        $itemToPurchase->status = 'sold';
        $itemToPurchase->save();

        // 商品にカテゴリを紐付け（Seederのカテゴリを使用）
        $itemCategories = Category::all()->take(2);
        $itemToPurchase->categories()->sync($itemCategories->pluck('id'));

        // コメントを作成
        Comment::create([
            'item_id' => $itemToPurchase->id,
            'user_id' => $buyer->id,
            'comment' => 'テストコメントです',
        ]);
    }

    public function test_item_detail_page_displays_required_information()
    {
        $item = Item::first();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        // 商品基本情報
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee('¥' . number_format($item->price));
        $response->assertSee($item->description);

        // 購入済みなら sold 表示
        if ($item->status === 'sold') {
            $response->assertSee('sold');
        }

        // カテゴリ情報
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }

        // いいね数
        $response->assertSee((string)$item->favoritedBy()->count());

        // コメント数
        $response->assertSee((string)$item->comments()->count());

        // コメント内容・コメントしたユーザー名
        foreach ($item->comments as $comment) {
            $response->assertSee($comment->comment);
            $response->assertSee($comment->user->name);
        }
    }

    public function test_multiple_categories_are_displayed()
    {
        $item = Item::first();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        // 複数カテゴリが表示されているか確認
        $this->assertGreaterThanOrEqual(2, $item->categories->count());
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
