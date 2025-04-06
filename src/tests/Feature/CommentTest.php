<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * ログイン済みのユーザーはコメントを送信できる
     */
    public function testLoggedInUserCanComment()
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

        $item = Item::create([
            'name' => '商品1',
            'price' => 1000,
            'image' => 'some_image.jpg',
            'detail' => '商品詳細1',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'ブランド1',
        ]);
        $item->categories()->attach($category->id);

        $response = $this->post("/items/{$item->id}", [
            'comment' => 'これはテストコメントです。',
        ]);

         $item->refresh();
        $this->assertEquals(1, $item->comments()->count());

        $response->assertRedirect(route('item.detail', ['item' => $item->id]));

    }


    /** 
     * @test
     * ログイン前のユーザーはコメントを送信できない
     */
    public function testGuestUserCannotComment()
    {
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
            'name' => '商品1',
            'price' => 1000,
            'image' => 'some_image.jpg',
            'detail' => '商品詳細1',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'ブランド1',
        ]);
        $item->categories()->attach($category->id);

        $response = $this->post("/items/{$item->id}", [
            'comment' => 'これはテストコメントです。',
        ]);

        $response->assertStatus(302); 
        $response->assertRedirect(route('login')); 
    }

    /** 
     * @test
     * コメントが入力されていない場合、バリデーションメッセージが表示される
     */
    public function testCommentIsRequired()
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

        $item = Item::create([
            'name' => '商品1',
            'price' => 1000,
            'image' => 'some_image.jpg',
            'detail' => '商品詳細1',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'ブランド1',
        ]);
        $item->categories()->attach($category->id);

        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
            'comment' => ''
        ]);

        $response->assertSessionHasErrors('comment'); 
    }

    /**
     * コメントが256文字以上
     *
     * @return void
     */
    public function testCommentMustBeLessThan256Characters()
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

        $item = Item::create([
            'name' => '商品1',
            'price' => 1000,
            'image' => 'some_image.jpg',
            'detail' => '商品詳細1',
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'brand' => 'ブランド1',
        ]);
        $item->categories()->attach($category->id);

        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
            'comment' => str_repeat('a', 256) 
        ]);

        $response->assertSessionHasErrors('comment'); 
    }
}

