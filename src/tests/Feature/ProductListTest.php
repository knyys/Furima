<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class ProductListTest extends TestCase
{
    /**
     * 全商品表示
     *
     *
     * @return void
     */
    public function testAllItemsAreDisplayed()
    {
        $item1 = Item::factory()->create(['name' => '商品1', 'sold' => false]);
        $item2 = Item::factory()->create(['name' => '商品2', 'sold' => false]);

        $response = $this->get('/'); 

        $response->assertSee('商品1');
        $response->assertSee('商品2');
    }

    /**
     * 購入済み商品を確認
     *
     * @return void
     */
    public function testSoldItemsDisplaySoldLabel()
    {
        $item = Item::factory()->create(['name' => '商品1', 'sold' => true]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品が表示されない
     *
     * @return void
     */
    public function testItemsSoldByUserAreNotDisplayed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['user_id' => $user->id, 'name' => '自分の商品']);

        $response = $this->get('/'); 

        $response->assertDontSee('自分の商品');
}
}