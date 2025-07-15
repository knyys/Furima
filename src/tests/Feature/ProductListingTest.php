<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ProductListingTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * 商品出品画面にて必要な情報が保存できること
     */
    public function testProductListingIsSavedCorrectly()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $response = $this->get('/sell');
    
        $condition = Condition::create(['condition' => 'New']);
        $category = Category::create(['category' => 'Electronics']);

        // 出品処理
        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $response = $this->post('/sell', [
            'name' => 'Sample Product',
            'detail' => 'This is a sample product for testing.',
            'price' => 10000,
            'brand' => 'TestBrand',
            'condition' => 'New',
            'category' => ['Electronics'],
            'image' => $image, 
        ]);
        
        $this->assertDatabaseHas('items', [
            'name' => 'Sample Product',
            'detail' => 'This is a sample product for testing.',
            'price' => 10000,
            'brand' => 'TestBrand',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);
        $item = Item::where('name', 'Sample Product')->first();
        $this->assertTrue($item->categories->contains($category));

        $response->assertRedirect(route('mypage', ['item' => $item->id]));
    }
}

