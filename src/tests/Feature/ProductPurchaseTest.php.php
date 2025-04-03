<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 購入完了
     *
     * @return void
     */
    public function testUserCanPurchaseItem()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('purchase', $item->id));
        $response->assertStatus(200); 

        $response = $this->post(route('purchase.complete', $item->id));

        $item->refresh();
        $this->assertEquals('sold', $item->status); 

        $response->assertSessionHas('success', '購入が完了しました。');
    }

    /**
     * 購入した商品
     *
     * @return void
     */
    public function testPurchasedItemIsDisplayedAsSoldInItemList()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);
        $this->post(route('purchase.complete', $item->id));

        $response = $this->get('/');

        $response->assertSee('sold');
    }

    /**
     * 購入した商品は購入した商品一覧に追加
     *
     * @return void
     */
    public function testPurchasedItemIsAddedToProfilePurchaseList()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);
        $this->post(route('purchase.complete', $item->id));

        $response = $this->get(route('mypage'));

        $response->assertSee($item->name); 
    }
}
