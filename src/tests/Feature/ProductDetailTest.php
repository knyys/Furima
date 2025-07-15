<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 必要な情報が表示される
     */
    public function testProductDetailDisplaysAllNecessaryInformation() 
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
            'name' => '商品',
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
        $comment = Comment::create([
            'item_id' => $item->id,
            'user_id' => $user2->id,
            'comment' => 'これはテストコメントです。',
        ]);
        Like::create(['user_id' => $user2->id, 'item_id' => $item->id]);

        $response = $this->get(route('item.detail', ['item' => $item->id]));
        
        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee(number_format($item->price)); 
        $response->assertSee($item->detail); 
        $response->assertSee($item->brand);
        $response->assertSee($category->category); 
        $response->assertSee($comment->comment); 
        $response->assertSee($comment->user->name); 

        $response->assertSee(asset( 'storage/' . $item->image));

        if ($comment->user->profile) {
            $response->assertSee($comment->user->profile->image);
        }

        $response->assertSee($item->likes->count());
    }



    /**
     * @test
     * 複数選択されたカテゴリが表示されているか
     */
    public function testMultipleCategoriesAreDisplayedOnProductDetail()
    {
        $user1 = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user1);


        $condition = Condition::create(['condition' => '新品']);

        $category1 = Category::create(['category' => '腕時計']);
        $category2 = Category::create(['category' => 'メンズ']);

        $item = Item::create([
            'name' => '商品',
            'price' => 15000,
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => $condition->id,
            'user_id' => $user1->id,
            'brand' => 'COACH',
        ]);
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get(route('item.detail', $item->id));

        $response->assertSee($category1->category);
        $response->assertSee($category2->category);
    }
}

