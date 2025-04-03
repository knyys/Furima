<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListingTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function testProductListingIsSavedCorrectly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/sell');

        $response = $this->post('/sell', [
            'category' => 'Electronics', 
            'condition' => 'New',
            'name' => 'Sample Product', 
            'description' => 'This is a sample product for testing.', 
            'price' => 10000, 
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Sample Product',
            'category' => 'Electronics',
            'condition' => 'New',
            'description' => 'This is a sample product for testing.',
            'price' => 10000,
            'user_id' => $user->id, 
        ]);

        $response->assertRedirect('/');
    }

    /**
     * 未認証ユーザーがアクセスした場合
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessProductListingPage()
    {
        $response = $this->get('/sell');

        $response->assertRedirect(route('login'));
    }
}

