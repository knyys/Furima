<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザーが商品出品画面で情報を入力して商品を保存できるかのテスト
     *
     * @return void
     */
    public function testProductListingIsSavedCorrectly()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();

        // ログイン
        $this->actingAs($user);

        // 商品出品ページを開く
        $response = $this->get('/sell');

        // 必要な情報を送信して商品を作成
        $response = $this->post('/sell', [
            'category' => 'Electronics', // カテゴリ
            'condition' => 'New', // 商品の状態
            'name' => 'Sample Product', // 商品名
            'description' => 'This is a sample product for testing.', // 商品の説明
            'price' => 10000, // 販売価格
        ]);

        // 商品が正しく保存されているかを確認
        $this->assertDatabaseHas('products', [
            'name' => 'Sample Product',
            'category' => 'Electronics',
            'condition' => 'New',
            'description' => 'This is a sample product for testing.',
            'price' => 10000,
            'user_id' => $user->id, // 商品を出品したユーザー
        ]);

        // 商品一覧画面にリダイレクトされることを確認
        $response->assertRedirect('/');
    }

    /**
     * 出品ページに未認証ユーザーがアクセスした場合のテスト
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessProductListingPage()
    {
        // 未認証ユーザーが商品出品ページにアクセス
        $response = $this->get('/sell');

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }
}

