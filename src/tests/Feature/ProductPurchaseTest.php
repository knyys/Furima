<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Sold;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     * 「購入する」ボタンを押下すると購入が完了する
     */
    public function testUserCanPurchaseItem()
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

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user2);

        // プロフィール作成
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        Profile::create([
            'user_id' => $user2->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
        ]);

        // Stripe
        $mock = Mockery::mock('overload:' . \Stripe\Checkout\Session::class);
        $mock->shouldReceive('create')->andReturn((object)[
            'url' => route('mypage', ['page' => 'buy']),
            'payment_method_types' => ['card'],
        ]);

        $response = $this->post(route('purchase.complete', $item->id), [
            'method' => 'カード支払い',
        ]);

        $this->assertDatabaseHas('solds', [
            'user_id' => $user2->id,
            'item_id' => $item->id,
            'sold' => true,
        ]);

        $sold = Sold::where('item_id', $item->id)->first();
        $this->assertNotNull($sold);
        $this->assertEquals($user2->id, $sold->user_id);
        $this->assertEquals($item->id, $sold->item_id);

        // リダイレクト先の確認
        $response->assertRedirect(route('mypage', ['page' => 'buy']));
    }

    /**
     * @test
     * 購入した商品は商品一覧画面にて「sold」と表示される
     */
    public function testPurchasedItemIsDisplayedAsSoldInItemList()
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

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user2);

        // プロフィール作成
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        Profile::create([
            'user_id' => $user2->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
        ]);

        $this->post(route('purchase.complete', $item->id));

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    /**
     * @test
     * 「プロフィール/購入した商品一覧」に追加されている
     */
    public function testPurchasedItemIsAddedToProfilePurchaseList()
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

        $user2 = User::create([
            'name' => 'Test User2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user2);
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        Profile::create([
            'user_id' => $user2->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
        ]);


        $this->post(route('purchase.complete', $item->id)); // 購入完了

        Sold::create([
        'user_id' => $user2->id,
        'item_id' => $item->id,
        'sold' => 1, 
        'method' => 'card',
        'address_number' => '000-0000',
        'address' => 'aaaaa',
        'building' => 'bbbbb',
        ]);

        $response = $this->get(route('mypage', ['page' => 'buy'])); 

        $response->assertSee($item->name);
    }
}
