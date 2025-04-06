<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Sold;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * いいねした商品だけが表示される
     */
    public function testLikedItemsAreDisplayedInMyList()
    {
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

        $item1 = Item::create([
            'name' => '商品1',
            'price' => 1000,
            'image' => 'some_image.jpg',
            'detail' => '商品詳細1',
            'condition_id' => $condition -> id,
            'user_id' => $user->id,
            'brand' => 'ブランド1',
        ]);
        $item1->categories()->attach($category->id);

        $item2 = Item::create([
            'name' => '商品2',
            'price' => 2000,
            'image' => 'some_image2.jpg',
            'detail' => '商品詳細2',
            'condition_id' => $condition -> id,
            'user_id' => $user->id,
            'brand' => 'ブランド2',
        ]);
        $item2->categories()->attach($category->id);

        // いいねを追加
        Like::create(['user_id' => $user->id, 'item_id' => $item1->id]);

        $response = $this->get('/?page=mylist&item_name=');

        $response->assertSee('商品1'); 
        $response->assertDontSee('商品2');
    }
    

    /** 
     * @test
     * 購入済み商品は「Sold」と表示される
     */
    public function testSoldItemsDisplaySoldLabelInMyList()
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

        // マイリスト（いいね）に追加
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品が販売済みに
        Sold::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sold' => true,
            'method' => 'card',
            'address_number' => '123-4567',
            'address' => 'Japan',
            'building' => 'Building',
        ]);

        // 認証状態でアクセス
        $response = $this->actingAs($user)->get('/?page=mylist&item_name=');

        // Sold ラベルが表示されていることを確認
        $response->assertSee('Sold');
    }


    /** 
     * @test
     * 自分が出品した商品は表示されない
     */
    public function testItemsSoldByUserAreNotDisplayedlInMyList()
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

        $response = $this->get('/?page=mylist&item_name='); 
        $response->assertDontSee('自分の商品');
    }

    /** 
     * @test
     * 未認証の場合は何も表示されない
     */
    public function testGuestsCannotSeeMyList()
    {
        $response = $this->get('/?page=mylist&item_name=');

        $response->assertStatus(302);
    }
}
