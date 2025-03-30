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
     * 送付先住所変更画面で登録した住所が商品購入画面に反映されることを確認するテスト
     *
     * @return void
     */
    public function testShippingAddressIsReflectedOnProductPurchaseScreen()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーがログイン
        $this->actingAs($user);

        // 送付先住所変更画面を表示
        $response = $this->get(route('shipping.address.edit'));
        $response->assertStatus(200); // 住所変更画面が表示されることを確認

        // 住所を登録（仮の住所）
        $addressData = [
            'address' => '東京都渋谷区1-1-1',
            'postcode' => '150-0001',
            'phone' => '080-1234-5678'
        ];

        // 住所登録フォームを送信
        $response = $this->post(route('shipping.address.update'), $addressData);

        // 商品購入画面に遷移
        $response = $this->get(route('product.purchase'));
        $response->assertStatus(200); // 商品購入画面が表示されることを確認

        // 登録した住所が商品購入画面に反映されていることを確認
        $response->assertSee($addressData['address']); // 住所が画面に表示されていることを確認
        $response->assertSee($addressData['postcode']); // 郵便番号が画面に表示されていることを確認
        $response->assertSee($addressData['phone']); // 電話番号が画面に表示されていることを確認
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録されることを確認するテスト
     *
     * @return void
     */
    public function testShippingAddressIsAssociatedWithThePurchasedProduct()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーがログイン
        $this->actingAs($user);

        // 送付先住所変更画面を表示
        $response = $this->get(route('shipping.address.edit'));
        $response->assertStatus(200); // 住所変更画面が表示されることを確認

        // 住所を登録（仮の住所）
        $addressData = [
            'address' => '東京都渋谷区1-1-1',
            'postcode' => '150-0001',
            'phone' => '080-1234-5678'
        ];

        // 住所登録フォームを送信
        $response = $this->post(route('shipping.address.update'), $addressData);

        // 商品購入画面に遷移し、購入処理を行う
        $response = $this->post(route('product.purchase', ['item' => $item->id]));

        // 購入完了後、購入した商品に住所が紐づいていることを確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => $addressData['address'],
            'shipping_postcode' => $addressData['postcode'],
            'shipping_phone' => $addressData['phone']
        ]);
    }
}
