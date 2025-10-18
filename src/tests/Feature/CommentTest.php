<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Condition;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected $condition;

    protected function setUp(): void
    {
        parent::setUp();

        // 条件データを作成（外部キー用）
        $this->condition = Condition::create(['id' => 1, 'name' => '良好']);
    }

    protected function createUser($name, $email)
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
        ]);
    }

    protected function createItem($user)
    {
        return Item::create([
            'user_id' => $user->id,
            'condition_id' => $this->condition->id,
            'name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'テスト用の腕時計です',
            'price' => 15000,
            'status' => 'active',
            'path' => 'items/test.jpg',
        ]);
    }

    /** @test */
    public function ログイン済みユーザーはコメントを投稿できる()
    {
        $user = $this->createUser('テストユーザー1', 'user1@example.com');
        $item = $this->createItem($user);

        $response = $this->actingAs($user)
                         ->post(route('comments.store', ['item_id' => $item->id]), [
                             'comment' => 'テストコメントです',
                         ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメントです',
        ]);
    }

    /** @test */
    public function ゲストはコメントを投稿できない()
    {
        $user = $this->createUser('テストユーザー2', 'user2@example.com');
        $item = $this->createItem($user);

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'comment' => 'ゲストコメント',
        ]);

        $response->assertStatus(302); // ログインページへリダイレクト
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストコメント',
        ]);
    }

    /** @test */
    public function コメントは空では投稿できない()
    {
        $user = $this->createUser('テストユーザー3', 'user3@example.com');
        $item = $this->createItem($user);

        $response = $this->actingAs($user)
                         ->post(route('comments.store', ['item_id' => $item->id]), [
                             'comment' => '',
                         ]);

        $response->assertSessionHasErrors('comment');
        $this->assertEquals(0, $item->fresh()->comments()->count());
    }

    /** @test */
    public function コメントは255文字を超えて投稿できない()
    {
        $user = $this->createUser('テストユーザー4', 'user4@example.com');
        $item = $this->createItem($user);

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
                         ->post(route('comments.store', ['item_id' => $item->id]), [
                             'comment' => $longComment,
                         ]);

        $response->assertSessionHasErrors('comment');
        $this->assertEquals(0, $item->fresh()->comments()->count());
    }
}
