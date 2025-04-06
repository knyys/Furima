<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * 小計画面で変更が即時反映される
     */
    public function testPaymentMethodIsReflectedImmediatelyOnSubtotalScreen()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '腕時計']);
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

        Session::put('payment_method', 'カード支払い'); // 'カード支払い' をセッションに保存

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertStatus(200); 

        $response->assertSessionHas('payment_method', 'カード支払い'); 
        $response->assertSee('カード支払い'); 

        $response = $this->get(route('purchase', ['item' => $item->id]));
        $response->assertSee('カード支払い'); 
    }
}

