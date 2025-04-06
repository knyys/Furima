<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Condition;
use App\Models\Sold;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 必要な情報が取得できる
     */
    public function testUserProfileInformationIsDisplayedCorrectly()
    {
        // ユーザー1作成
        $user1 = User::create([
            'name' => '自分',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user1);

        // ユーザー2作成
        $user2 = User::create([
            'name' => '出品者',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);

        // 自分のプロフィール作成
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        Profile::create([
            'user_id' => $user1->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
            ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '家電']);

        // 自分が出品した商品
        $userItems = Item::create([
            'name' => '出品商品',
            'image' => 'images/HDD+Hard+Disk.jpg',
            'detail' => '高速で信頼性の高いハードディスク',
            'price' => 10000,
            'brand' => 'Test',
            'condition_id' => $condition->id,
            'user_id' => $user1->id,
        ]);
        $userItems->categories()->attach($category->id);

        $this->actingAs($user2);
        // ユーザー2が出品した商品
        $item = Item::create([
            'name' => '購入商品',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user2->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach($category->id);

        $this->actingAs($user1);
        // ユーザー1が購入
        $purchasedItems = Sold::create([
            'user_id' => $user1->id,
            'item_id' => $item->id,
            'sold' => true,
            'method' => 'card',
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
        ]);

        // マイページを表示
        $response = $this->get('/mypage');

        // レスポンスを確認
        $response->assertStatus(200);
        $response->assertSee($user1->name);
        $response->assertSee(url('storage/profile/sample.jpg')); 
        $response->assertSee($userItems->name);
        $response->assertSee($purchasedItems ->name);
    }

}
