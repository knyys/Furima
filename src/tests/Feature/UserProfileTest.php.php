<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィールページ
     *
     * @return void
     */
    public function testUserProfileInformationIsDisplayedCorrectly()
    {
        $user = User::factory()->create([
            'profile_image' => 'profile_image.jpg', 
            'name' => 'Test User', 
        ]);

        $user->products()->create([
            'name' => '商品1',
            'price' => 1000,
            'status' => 'available'
        ]);

        $user->purchases()->create([
            'product_name' => '購入商品1',
            'price' => 500,
            'status' => 'sold'
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee($user->name); 
        $response->assertSee($user->profile_image); 
        $response->assertSee('商品1'); 
        $response->assertSee('購入商品1');
    }

    /**
     * 未認証時プロフィールページにアクセス
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessProfilePage()
    {
        $response = $this->get('/profile');
        
        $response->assertRedirect(route('login'));
    }
}
