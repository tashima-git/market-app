<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemListTest extends TestCase
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

        // 追加で購入済み商品を作成
        $buyer = User::first(); // 適当な購入者
        $itemToPurchase = Item::where('id', 1)->first(); // 任意の商品
        Purchase::create([
            'item_id' => $itemToPurchase->id,
            'user_id' => $buyer->id,
            'address' => '東京都千代田区1-1-1',
            'payment_method' => 'credit_card',
        ]);

            // 重要：Item のステータスを sold に更新
    $itemToPurchase->status = 'sold';
    $itemToPurchase->save();
    }

    public function test_all_items_are_displayed()
{
    $response = $this->get('/'); // 商品一覧ページ
    $response->assertStatus(200);

    // データベースに登録されている全商品名を確認
    $allItems = \App\Models\Item::all();
    foreach ($allItems as $item) {
        $response->assertSee($item->name);
    }
}


    public function test_purchased_items_are_marked_as_sold()
    {
        $response = $this->get('/');

        // 購入済み商品の「Sold」表示を確認
        $response->assertSee('sold');
    }

    public function test_user_items_are_not_displayed_to_self()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/');

        // 自分の出品商品が表示されないことを確認
        $userItems = Item::where('user_id', $user->id)->get();
        foreach ($userItems as $item) {
            $response->assertDontSee($item->name);
        }
    }
}
