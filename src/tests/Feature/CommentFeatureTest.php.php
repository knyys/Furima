<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();

        // テスト用ユーザーを作成
        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 商品を作成
        $this->item = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Product',
        ]);
    }

    /**
     * ログイン済みのユーザーはコメントを送信できることを確認するテスト
     *
     * @return void
     */
    public function testLoggedInUserCanComment()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーがコメントを送信
        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
        'comment' => '素晴らしい商品です！'
    ]);

        // コメントが保存され、コメント数が増加することを確認
        $item->refresh();
        $this->assertEquals(1, $item->comments()->count()); // 商品のコメント数が1になっている
        $response->assertStatus(200); // レスポンスが200であることを確認
    }

    /**
     * ログイン前のユーザーはコメントを送信できないことを確認するテスト
     *
     * @return void
     */
    public function testGuestUserCannotComment()
    {
        // 商品を作成
        $item = Item::factory()->create();

        // ログインしていない状態でコメントを送信しようとする
        $response = $this->post(route('item.detail', $item->id), [
            'comment' => '素晴らしい商品です！'
        ]);

        // コメントが送信されないことを確認
        $response->assertStatus(302); // リダイレクトされることを確認
        $response->assertRedirect(route('login')); // ログインページにリダイレクトされる
    }

    /**
     * コメントが入力されていない場合、バリデーションメッセージが表示されることを確認するテスト
     *
     * @return void
     */
    public function testCommentIsRequired()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // コメントを入力せずに送信
        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
            'comment' => ''
        ]);

        // バリデーションメッセージが表示されることを確認
        $response->assertSessionHasErrors('comment'); // 'comment' フィールドにエラーがあることを確認
    }

    /**
     * コメントが256文字以上の場合、バリデーションメッセージが表示されることを確認するテスト
     *
     * @return void
     */
    public function testCommentMustBeLessThan256Characters()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 256文字以上のコメントを入力
        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
            'comment' => str_repeat('a', 256) // 256文字のコメント
        ]);

        // バリデーションメッセージが表示されることを確認
        $response->assertSessionHasErrors('comment'); // 'comment' フィールドにエラーがあることを確認
    }
}

