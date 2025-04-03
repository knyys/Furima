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
     * いいねをした商品マイリスト表示
     * 
     *
     * @return void
     */
    public function testLikedItemsAreDisplayedInMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item1 = Item::factory()->create(['name' => '商品1']);
        $item2 = Item::factory()->create(['name' => '商品2']);

        Like::create(['user_id' => $user->id, 'item_id' => $item1->id]);

        $response = $this->get('/mylist'); 

        $response->assertSee('商品1');
        $response->assertDontSee('商品2'); 
    }

    /**
     * 購入済み商品を確認する
     *
     * @return void
     */
    public function testSoldItemsDisplaySoldLabelInMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['name' => '商品1', 'sold' => true]);

        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get('/mylist'); 

        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品がはイリストに表示されない
     *
     * @return void
     */
    public function testItemsSoldByUserAreNotDisplayedlInMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['user_id' => $user->id, 'name' => '自分の商品']);

        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get('/mylist'); 
        $response->assertDontSee('自分の商品');
    }

    /**
     * 未認証の場合
     *
     * @return void
     */
    public function testGuestsCannotSeeMyList()
    {
        $response = $this->get('/mylist');

        $response->assertStatus(302); 
    }
}
