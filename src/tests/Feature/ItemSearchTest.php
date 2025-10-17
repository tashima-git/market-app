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

    /**
     * ホームページで部分一致検索できること
     */
    public function test_homepage_search_returns_partial_match_items()
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

    /**
     * 検索キーワードがマイリストでも保持されること
     */
    public function test_search_keyword_is_preserved_on_mylist_page()
    {
        $user = User::first();
        $this->actingAs($user);

        // ホームページで検索
        $keyword = '時計';
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
