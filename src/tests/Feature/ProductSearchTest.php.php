<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品名で部分一致検索ができることを確認するテスト
     *
     * @return void
     */
    public function testProductNameSearchWithPartialMatch()
    {
        // 商品を作成
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);
        $item3 = Item::factory()->create(['name' => '商品C']);

        // 検索キーワード「商品A」で商品を検索
        $response = $this->get('/?item_name=商品A');

        // 部分一致する商品が表示されることを確認
        $response->assertSee('商品A');
        $response->assertDontSee('商品B');
        $response->assertDontSee('商品C');
    }

    /**
     * 商品検索状態がマイリストでも保持されていることを確認するテスト
     *
     * @return void
     */
    public function testSearchStateIsMaintainedOnMyListPage()
    {
        // 商品を作成
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);
        $item3 = Item::factory()->create(['name' => '商品C']);

        // 検索キーワード「商品A」で商品を検索
        $response = $this->get('/?item_name=商品A');

        // 検索結果が表示されることを確認
        $response->assertSee('商品A');
        $response->assertDontSee('商品B');
        $response->assertDontSee('商品C');

        // マイリストページに遷移
        $response = $this->get('/mylist');

        // 検索キーワードが保持されていることを確認
        $response->assertSee('商品A');
    }
}

