<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 送付先住所変更画面で登録した住所が商品購入画面に反映される
     *
     * @return void
     */
    public function testShippingAddressIsReflectedOnProductPurchaseScreen()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/purchase/address/{item}',  $item->id );
        $response->assertStatus(200); 

        $addressData = [
            'address' => '東京都渋谷区1-1-1',
            'postcode' => '150-0001',
            'phone' => '080-1234-5678'
        ];

        $response = $this->post(route('address.update'), $addressData);

        $response = $this->get(route('purchase'));
        $response->assertStatus(200); 

        $response->assertSee($addressData['address']); 
        $response->assertSee($addressData['postcode']); 
        $response->assertSee($addressData['phone']);
    }

    /**
     * 購入した商品に送付先住所が紐づいている
     *
     * @return void
     */
    public function testShippingAddressIsAssociatedWithThePurchasedProduct()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/purchase/address/{item}',  $item->id );
        $response->assertStatus(200); 

        $addressData = [
            'address' => '東京都渋谷区1-1-1',
            'postcode' => '150-0001',
            'phone' => '080-1234-5678'
        ];

        $response = $this->post(route('address.update'), $addressData);

        $response = $this->post(route('purchase', ['item' => $item->id]));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => $addressData['address'],
            'shipping_postcode' => $addressData['postcode'],
            'shipping_phone' => $addressData['phone']
        ]);
    }
}
