<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test 
     * 「商品名」で部分一致検索ができる
     */
    public function testProductNameSearchWithPartialMatch()
    {
        $user1 = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '腕時計']);
        $item = Item::create([
            'name' => '商品1',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user1->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach($category->id);

        $item2 = Item::create([
            'name' => '商品2',
            'image' => 'images/HDD+Hard+Disk.jpg',
            'detail' => '高速で信頼性の高いハードディスク',
            'price' => 10000,
            'brand' => 'Test',
            'condition_id' => $condition->id,
            'user_id' => $user1->id,
        ]);
        $item2->categories()->attach($category->id);

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->get('/?item_name=商品');

        $response->assertSee('商品1');
        $response->assertSee('商品2');
    }

    /**
     * @test 
     * 検索状態がマイリストでも保持されている
     */
    public function testSearchStateIsMaintainedOnMyListPage()
    {
        $user1 = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user1);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '腕時計']);
        $item = Item::create([
            'name' => '商品1',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user1->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach($category->id);

        $item2 = Item::create([
            'name' => '商品2',
            'image' => 'images/HDD+Hard+Disk.jpg',
            'detail' => '高速で信頼性の高いハードディスク',
            'price' => 10000,
            'brand' => 'Test',
            'condition_id' => $condition->id,
            'user_id' => $user1->id,
        ]);
        $item2->categories()->attach($category->id);

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user2);

        $response = $this->get('/?item_name=商品&page=mylist');

        $response->assertSee('商品1');
        $response->assertSee('商品2');

    }
}

