<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     *商品のいいね確認
     *
     * @return void
     */
    public function testLikeItem()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('item.detail', $item->id));

        $response = $this->actingAs($user)->post(route('item.detail', $item->id));

        $item->refresh();  
        $this->assertEquals(1, $item->likes_count); 


        $response->assertSee('liked');  
    }

    /**
     * いいね解除確認
     *
     * @return void
     */
    public function testUnlikeItem()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post('/like/{post}', $item->id);

        $response = $this->actingAs($user)->post('/like/{post}', $item->id);

        $item->refresh();
        $this->assertEquals(0, $item->likes_count); 

        $response->assertDontSee('liked');  
    }
}