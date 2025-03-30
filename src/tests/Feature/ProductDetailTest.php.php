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
        // 商品、カテゴリ、コメント、ユーザーを作成
        $item = Item::factory()->create([
            'name' => '商品A',
            'price' => 1000,
            'description' => '商品Aの説明',
            'brand' => 'ブランドA',
        ]);
        
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);
        
        $item->categories()->attach([$category1->id, $category2->id]);  // 商品に複数のカテゴリを関連付け
        
        $user = User::factory()->create();  // コメントするユーザーを作成
        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'これはテストコメントです。',
        ]);

        // 商品詳細ページを開く
        $response = $this->get(route('products.show', $item->id));

        // 商品詳細ページに必要な情報がすべて表示されていることを確認
        $response->assertSee($item->name);
        $response->assertSee($item->price);
        $response->assertSee($item->description);
        $response->assertSee($item->brand);
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
        $response->assertSee($comment->comment);
        $response->assertSee($user->name);  // コメントしたユーザー名
        $response->assertSee($comment->comment);
    }

    /**
     * 商品詳細ページに複数選択されたカテゴリが表示されることを確認するテスト
     *
     * @return void
     */
    public function testMultipleCategoriesAreDisplayedOnProductDetail()
    {
        // 商品、カテゴリを作成
        $item = Item::factory()->create([
            'name' => '商品B',
        ]);
        
        $category1 = Category::factory()->create(['name' => 'カテゴリA']);
        $category2 = Category::factory()->create(['name' => 'カテゴリB']);
        
        $item->categories()->attach([$category1->id, $category2->id]);  // 商品に複数のカテゴリを関連付け

        // 商品詳細ページを開く
        $response = $this->get(route('products.show', $item->id));

        // 複数選択されたカテゴリが表示されていることを確認
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }
}

