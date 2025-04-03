<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品詳細ページに必要な情報がすべて表示されることを確認するテスト
     *
     * @return void
     */
    public function testProductDetailDisplaysAllNecessaryInformation()
    {
        $item = Item::factory()->create([
            'name' => '商品A',
            'price' => 1000,
            'description' => '商品Aの説明',
            'brand' => 'ブランドA',
        ]);
        
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);
        
        $item->categories()->attach([$category1->id, $category2->id]);  
        
        $user = User::factory()->create();  
        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'これはテストコメントです。',
        ]);

        $response = $this->get(route('item.detail', $item->id));

        $response->assertSee($item->name);
        $response->assertSee($item->price);
        $response->assertSee($item->description);
        $response->assertSee($item->brand);
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
        $response->assertSee($comment->comment);
        $response->assertSee($user->name); 
        $response->assertSee($comment->comment);
    }

    /**
     * カテゴリー複数選択
     *
     * @return void
     */
    public function testMultipleCategoriesAreDisplayedOnProductDetail()
    {
        $item = Item::factory()->create([
            'name' => '商品B',
        ]);
        
        $category1 = Category::factory()->create(['name' => 'カテゴリA']);
        $category2 = Category::factory()->create(['name' => 'カテゴリB']);
        
        $item->categories()->attach([$category1->id, $category2->id]);  

        $response = $this->get(route('item.detail', $item->id));

        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }
}

