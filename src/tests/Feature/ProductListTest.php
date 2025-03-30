<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    /**
     * 全商品が表示されることを確認するテスト
     *
     * @return void
     */
    public function testAllItemsAreDisplayed()
    {
        // 商品を複数作成
        $item1 = Item::factory()->create(['name' => '商品1', 'sold' => false]);
        $item2 = Item::factory()->create(['name' => '商品2', 'sold' => false]);

        // 商品一覧ページにアクセス
        $response = $this->get('/'); // 商品ページのURL

        // 商品が表示されることを確認
        $response->assertSee('商品1');
        $response->assertSee('商品2');
    }

    /**
     * 購入済み商品に「Sold」のラベルが表示されることを確認するテスト
     *
     * @return void
     */
    public function testSoldItemsDisplaySoldLabel()
    {
        // 購入済みの商品を作成
        $item = Item::factory()->create(['name' => '商品1', 'sold' => true]);

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // 「Sold」のラベルが表示されていることを確認
        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品が表示されないことを確認するテスト
     *
     * @return void
     */
    public function testItemsSoldByUserAreNotDisplayed()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // ユーザーが出品した商品を作成
        $item = Item::factory()->create(['user_id' => $user->id, 'name' => '自分の商品']);

        // 商品一覧ページにアクセス
        $response = $this->get('/'); // 商品ページのURL

        // 自分が出品した商品が表示されていないことを確認
        $response->assertDontSee('自分の商品');
}
}