<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 必要なシーダーを実行
        $this->seed(\Database\Seeders\ConditionSeeder::class);
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\ItemSeeder::class);
    }

    /** @test */
    public function ホームページで部分一致検索できる()
    {
        $keyword = '時計'; // 部分一致キーワード
        $response = $this->get('/?keyword=' . $keyword);

        $response->assertStatus(200);

        // 検索にマッチする商品名を確認
        $matchingItems = Item::where('name', 'like', "%{$keyword}%")->get();
        foreach ($matchingItems as $item) {
            $response->assertSee($item->name);
        }

        // 検索にマッチしない商品は表示されない
        $nonMatchingItems = Item::where('name', 'not like', "%{$keyword}%")->get();
        foreach ($nonMatchingItems as $item) {
            $response->assertDontSee($item->name);
        }
    }

    /** @test */
    public function 検索キーワードがマイリストでも保持される()
    {
        $user = User::first();
        $this->actingAs($user);

        $keyword = '時計';

        // 検索対象の商品を作成（または既存商品を取得）
        $item = Item::where('name', 'like', "%{$keyword}%")->first();

        // ログインユーザーのお気に入りに追加
        $user->favoriteItems()->attach($item->id);

        // ホームページで検索
        $responseHome = $this->get('/?keyword=' . $keyword);
        $responseHome->assertStatus(200);

        // 検索結果が表示されていること
        $matchingItems = Item::where('name', 'like', "%{$keyword}%")->get();
        foreach ($matchingItems as $item) {
            $responseHome->assertSee($item->name);
        }

        // マイリストページに遷移（検索キーワードを保持）
        $responseMylist = $this->get('/mylist?keyword=' . $keyword);
        $responseMylist->assertStatus(200);

        // 検索結果がマイリストでも保持されていることを確認
        $matchingFavoriteItems = $user->favoriteItems()
            ->where('name', 'like', "%{$keyword}%")
            ->get();

        foreach ($matchingFavoriteItems as $item) {
            $responseMylist->assertSee($item->name);
        }

        // 検索にマッチしないお気に入りは表示されない
        $nonMatchingFavoriteItems = $user->favoriteItems()
            ->where('name', 'not like', "%{$keyword}%")
            ->get();

        foreach ($nonMatchingFavoriteItems as $item) {
            $responseMylist->assertDontSee($item->name);
        }

        // 検索キーワードがフォームに保持されていることを確認
        $responseMylist->assertSee('value="' . $keyword . '"', false);
    }
}
