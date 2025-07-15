<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * いいねアイコンを押下することによって、いいねした商品として登録することができる
     */
    public function testLikeItemAndRegisterLike()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        // 商品作成
        $condition = Condition::create([
            'condition' => '新品',
        ]);
        $category = Category::create([
            'category' => '腕時計',
        ]);
        $item = Item::create([
            'name' => '腕時計',
            'price' => 10000,
            'image' => 'images/test.jpg',
            'detail' => 'テスト用の腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'SEIKO',
        ]);
        $item->categories()->attach($category->id);

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user2); 

        $response = $this->get(route('item.detail', ['item' => $item->id]));

        $this->assertEquals(0, $item->likes()->count());

        // いいね
        $response = $this->json('POST', route('item.like', ['itemId' => $item->id]), []);
        $response->assertStatus(200)
                ->assertJson(['liked' => true]);
        $item->refresh();
        $this->assertEquals(1, $item->likes()->count());

        $response->assertJson(['likes_count' => 1]);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user2->id, 
            'item_id' => $item->id, 
        
        ]);
    }

    /** 
     * @test
     * 追加済みのアイコンは色が変化する
     */
    public function testUnlikeItem()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        // 商品作成
        $condition = Condition::create([
            'condition' => '新品',
        ]);
        $category = Category::create([
            'category' => '腕時計',
        ]);
        $item = Item::create([
            'name' => '腕時計',
            'price' => 10000,
            'image' => 'images/test.jpg',
            'detail' => 'テスト用の腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'SEIKO',
        ]);
        $item->categories()->attach($category->id);

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user2); 

        $response = $this->get(route('item.detail', ['item' => $item->id]));

        $this->assertEquals(0, $item->likes()->count());

        // いいね
        $response = $this->json('POST', route('item.like', ['itemId' => $item->id]), []);
        $response->assertStatus(200)
                ->assertJson(['liked' => true]);
        $item->refresh();
        $this->assertEquals(1, $item->likes()->count());

        $response = $this->get(route('item.detail', ['item' => $item->id]));
        $response->assertSee('liked');

        $this->assertDatabaseHas('likes', [
            'user_id' => $user2->id, 
            'item_id' => $item->id, 
        ]);
    }

    /** 
     * 再度いいねアイコンを押下することによって、いいねを解除することができる
     */
    public function testToggleLikeIcon()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        // 商品作成
        $condition = Condition::create([
                'condition' => '新品',
            ]);
        $category = Category::create([
            'category' => '腕時計',
        ]);
        $item = Item::create([
            'name' => '腕時計',
            'price' => 10000,
            'image' => 'images/test.jpg',
            'detail' => 'テスト用の腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'SEIKO',
        ]);
        $item->categories()->attach($category->id);


        $user2 = User::create([
                'name' => 'Test User2',
                'email' => 'testuser2@example.com',
                'password' => bcrypt('password'),
            ]);
        $this->actingAs($user2); 

        $response = $this->get(route('item.detail', ['item' => $item->id]));

        $this->assertEquals(0, $item->likes()->count());

        // いいね
        $response = $this->json('POST', route('item.like', ['itemId' => $item->id]), []);
        $response->assertStatus(200)
                ->assertJson(['liked' => true]);
        $item->refresh();
        $this->assertEquals(1, $item->likes()->count());

        // いいねを解除
        $response = $this->json('POST', route('item.like', ['itemId' => $item->id]), []);
        $response->assertStatus(200)
                ->assertJson(['liked' => false]);

        // 商品の「いいね」カウントが0
        $item->refresh();
        $this->assertEquals(0, $item->likes()->count());

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user2->id, 
            'item_id' => $item->id, 
        ]);
    }
}