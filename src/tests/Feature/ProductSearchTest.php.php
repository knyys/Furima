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
     * 商品名で部分一致検索
     *
     * @return void
     */
    public function testProductNameSearchWithPartialMatch()
    {
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);
        $item3 = Item::factory()->create(['name' => '商品C']);

        $response = $this->get('/?item_name=商品A');

        $response->assertSee('商品A');
        $response->assertDontSee('商品B');
        $response->assertDontSee('商品C');
    }

    /**
     * 商品検索状態がマイリストでも保持
     *
     * @return void
     */
    public function testSearchStateIsMaintainedOnMyListPage()
    {
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);
        $item3 = Item::factory()->create(['name' => '商品C']);

        $response = $this->get('/?item_name=商品A');

        $response->assertSee('商品A');
        $response->assertDontSee('商品B');
        $response->assertDontSee('商品C');

        $response = $this->get('/mylist');

        $response->assertSee('商品A');
    }
}

