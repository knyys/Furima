<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねアイコンを押下して、商品がいいねされたことを確認するテスト
     *
     * @return void
     */
    public function testLikeItem()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーが商品詳細ページを訪問
        $response = $this->actingAs($user)->get(route('products.show', $item->id));

        // いいねアイコンを押す
        $response = $this->actingAs($user)->post(route('products.like', $item->id));

        // 商品のいいねが登録され、いいね合計値が増加することを確認
        $item->refresh();  // 商品データを再取得
        $this->assertEquals(1, $item->likes_count);  // いいね合計値が1になっている

        // いいねアイコンが押された状態で色が変化していることを確認
        $response->assertSee('liked');  // アイコンが押された状態で「liked」クラスが表示される
    }

    /**
     * いいねアイコンを再度押下して、いいねを解除できることを確認するテスト
     *
     * @return void
     */
    public function testUnlikeItem()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーが商品詳細ページを訪問して、いいねを押す
        $this->actingAs($user)->post(route('products.like', $item->id));

        // 再度いいねアイコンを押下して、いいねを解除
        $response = $this->actingAs($user)->post(route('products.like', $item->id));

        // 商品のいいねが解除され、いいね合計値が減少することを確認
        $item->refresh();
        $this->assertEquals(0, $item->likes_count);  // いいね合計値が0になっている

        // いいねアイコンが押されていない状態で色が変化していないことを確認
        $response->assertDontSee('liked');  // アイコンが押されていない状態で「liked」クラスが表示されない
    }
}
