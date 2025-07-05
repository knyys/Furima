<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use App\Services\StripeService;


class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close(); 
        parent::tearDown();
    }

    /**
     * @test 
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
     */
    public function testShippingAddressIsReflectedOnProductPurchaseScreen()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '時計']);
        $item = Item::create([
            'name' => '商品1',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach($category->id);

        // ユーザーのプロフィール作成
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        Profile::create([
            'user_id' => $user->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
        ]);

        // 送付先住所をセッションに設定
        $shippingAddress = [
            'address_number' => '150-0001',
            'address' => 'ccccc',
            'building' => '11111',
        ];
        Session::put('shipping_address', $shippingAddress);

        $response = $this->get("/purchase/address/{$item->id}");
        $response->assertStatus(200);

        // 住所変更を送信
        $response = $this->patch("/purchase/address/{$item->id}", $shippingAddress);
        $response->assertRedirect("/purchase/{$item->id}");

        // 商品購入画面を再度確認
        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee($shippingAddress['address']);
        $response->assertSee($shippingAddress['address_number']);
        $response->assertSee($shippingAddress['building']);
    }

    /**
     * @test 
     * 購入した商品に送付先住所が紐づいて登録される
     */
    public function testShippingAddressIsAssociatedWithThePurchasedProduct()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '時計']);
        $item = Item::create([
            'name' => '商品1',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach($category->id);

        // ユーザープロフィール作成
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        Profile::create([
            'user_id' => $user->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
        ]);

        // セッションに送付先住所を設定
        $shippingAddress = [
            'address_number' => '150-0001',
            'address' => 'dddd',
            'building' => '222',
        ];
        Session::put('shipping_address', $shippingAddress);

        $this->instance(StripeService::class, Mockery::mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('createCheckoutSession')->andReturn((object)[
                'url' => 'http://stripe.com/success',
                'payment_method_types' => ['card'],
            ]);
        }));

        $response = $this->post("/purchase/{$item->id}", [
            'method' => 'カード支払い',
        ]);

        // リダイレクト先のURLは特に検証せず、リダイレクトがあればOKとする
        $response->assertRedirect();

        // DBに購入履歴と住所が正しく登録されているか検証
        $this->assertDatabaseHas('solds', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'method' => 'card',
            'address_number' => $shippingAddress['address_number'],
            'address' => $shippingAddress['address'],
            'building' => $shippingAddress['building'],
        ]);
    }
}
