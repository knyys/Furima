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
     * 商品を購入した際に購入が完了することを確認するテスト
     *
     * @return void
     */
    public function testUserCanPurchaseItem()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーがログイン
        $this->actingAs($user);

        // 商品購入画面を開く
        $response = $this->get(route('product.purchase', $item->id));
        $response->assertStatus(200); // 商品購入画面が表示されることを確認

        // 購入ボタンを押下
        $response = $this->post(route('product.purchase.store', $item->id));

        // 購入が完了し、商品のステータスが "sold" になっていることを確認
        $item->refresh();
        $this->assertEquals('sold', $item->status); // 商品の状態が "sold" であることを確認

        // 購入完了メッセージが表示されることを確認
        $response->assertSessionHas('success', '購入が完了しました。');
    }

    /**
     * 購入した商品が商品一覧画面にて「sold」として表示されることを確認するテスト
     *
     * @return void
     */
    public function testPurchasedItemIsDisplayedAsSoldInItemList()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーが商品を購入
        $this->actingAs($user);
        $this->post(route('product.purchase.store', $item->id));

        // 商品一覧画面を表示
        $response = $this->get(route('product.index'));

        // 購入した商品が「sold」として表示されていることを確認
        $response->assertSee('sold'); // 商品一覧に「sold」というラベルが表示されていることを確認
    }

    /**
     * 購入した商品がプロフィール画面の購入した商品一覧に追加されていることを確認するテスト
     *
     * @return void
     */
    public function testPurchasedItemIsAddedToProfilePurchaseList()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーが商品を購入
        $this->actingAs($user);
        $this->post(route('product.purchase.store', $item->id));

        // プロフィール画面を表示
        $response = $this->get(route('profile.show'));

        // 購入した商品が「購入した商品一覧」に表示されていることを確認
        $response->assertSee($item->name); // プロフィール画面に購入した商品の名前が表示されていることを確認
    }
}
