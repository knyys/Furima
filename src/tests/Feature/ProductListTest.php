<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Sold;
use App\Models\Condition;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ProductListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * 全商品を取得できる
     */
    public function testAllItemsAreDisplayed()
    {
        // テストデータ作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $condition1 = Condition::create([
            'condition' => '新品',
        ]);
        $condition2 = Condition::create([
            'condition' => '目立った傷や汚れなし',
        ]);
        
        $category1 = Category::create([
            'category' => '腕時計',
        ]);
        $category2 = Category::create([
            'category' => '家電',
        ]);
        $item1 = Item::create([
            'name' => '腕時計',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition1->id,
            'user_id' => $user->id,
            'brand' => 'COACH',
        ]);
        $item1->categories()->attach($category1->id);

        $item2 = Item::create([
            'name' => 'HDD',
            'price' => 5000, 
            'image' => 'images/HDD+Hard+Disk.jpg',
            'detail' => '高速で信頼性の高いハードディスク',
            'condition_id' => $condition2->id,
            'user_id' => $user->id,
            'brand' => null
        ]);
        $item2->categories()->attach($category2->id);

        $response = $this->get('/');

        $response->assertSee('腕時計');
        $response->assertSee('HDD');
    }

    /**
     * @test
     * 購入済み商品は「Sold」と表示される
     */
    public function testSoldItemsDisplaySoldLabel()
    {
        // テストデータ作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $condition = Condition::create([
            'condition' => '新品',
        ]);
        $category = Category::create([
            'category' => '腕時計',
        ]);
        $item = Item::create([
            'name' => '腕時計',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach($category->id);

        Sold::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sold' => true,
            'method' => 'card',
            'address_number' => '123-4567',
            'address' => 'Japan',
            'building' => 'Building',
        ]);

        $response = $this->get(route('home', ['item' => $item->id]));

        $response->assertSee('Sold');
    }


    /**
     * @test
     * 自分が出品した商品は表示されない
     */
    public function testItemsSoldByUserAreNotDisplayed()
    {
        // テストデータ作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $condition = Condition::create([
            'condition' => '新品',
        ]);
        $category = Category::create([
            'category' => '腕時計',
        ]);
        $item = Item::create([
            'name' => '自分の商品',
            'price' => 1000,
            'image' => 'some_image.jpg',
            'detail' => '自分の商品詳細',
            'condition_id' => $condition->id,
            'user_id' => $user->id,  
            'brand' => 'ブランド名',
        ]);
        $item->categories()->attach($category->id);

        $response = $this->get('/'); 

        $response->assertDontSee('自分の商品');
    }
}