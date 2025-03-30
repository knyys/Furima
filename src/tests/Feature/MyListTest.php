<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねをした商品がマイリストに表示されることを確認するテスト
     *
     * @return void
     */
    public function testLikedItemsAreDisplayedInMyList()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 商品を作成
        $item1 = Item::factory()->create(['name' => '商品1']);
        $item2 = Item::factory()->create(['name' => '商品2']);

        // ユーザーが商品1をいいねする
        Like::create(['user_id' => $user->id, 'item_id' => $item1->id]);

        // マイリストページにアクセス
        $response = $this->get('/mylist'); // マイリストページのURL

        // いいねした商品が表示されることを確認
        $response->assertSee('商品1');
        $response->assertDontSee('商品2'); // いいねしていない商品は表示されない
    }

    /**
     * 購入済み商品に「Sold」のラベルが表示されることを確認するテスト
     *
     * @return void
     */
    public function testSoldItemsDisplaySoldLabelInMyList()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 商品を作成（購入済みの商品）
        $item = Item::factory()->create(['name' => '商品1', 'sold' => true]);

        // ユーザーが商品をいいねする
        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        // マイリストページにアクセス
        $response = $this->get('/mylist'); // マイリストページのURL

        // 「Sold」のラベルが表示されることを確認
        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品がマイリストに表示されないことを確認するテスト
     *
     * @return void
     */
    public function testItemsSoldByUserAreNotDisplayedlInMyList()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // ユーザーが出品した商品を作成
        $item = Item::factory()->create(['user_id' => $user->id, 'name' => '自分の商品']);

        // ユーザーが商品をいいねする
        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        // マイリストページにアクセス
        $response = $this->get('/mylist'); // マイリストページのURL

        // 自分が出品した商品がマイリストに表示されないことを確認
        $response->assertDontSee('自分の商品');
    }

    /**
     * 未認証の場合にマイリストが表示されないことを確認するテスト
     *
     * @return void
     */
    public function testGuestsCannotSeeMyList()
    {
        // 未認証の状態でマイリストページにアクセス
        $response = $this->get('/mylist');

        // 何も表示されないことを確認
        $response->assertStatus(302); // ログインを要求される
    }
}
