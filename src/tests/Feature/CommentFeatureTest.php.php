<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->item = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Product',
        ]);
    }

    /**
     * ログイン済はコメントを送信できる
     *
     * @return void
     */
    public function testLoggedInUserCanComment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
        'comment' => '素晴らしい商品です！'
    ]);

        $item->refresh();
        $this->assertEquals(1, $item->comments()->count()); 
        $response->assertStatus(200); 
    }

    /**
     * ログイン前はコメントを送信できない
     *
     * @return void
     */
    public function testGuestUserCannotComment()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('item.detail', $item->id), [
            'comment' => '素晴らしい商品です！'
        ]);

        $response->assertStatus(302); 
        $response->assertRedirect(route('login')); 
    }

    /**
     * コメントバリデーションメッセージ
     *
     * @return void
     */
    public function testCommentIsRequired()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

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
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.detail', $item->id), [
            'comment' => str_repeat('a', 256) 
        ]);

        $response->assertSessionHasErrors('comment'); 
    }
}

