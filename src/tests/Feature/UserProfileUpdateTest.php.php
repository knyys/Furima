<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィールページ
     *
     * @return void
     */
    public function testUserProfileInformationIsDisplayedWithInitialValues()
    {
        $user = User::factory()->create([
            'profile_image' => 'initial_profile_image.jpg',
            'name' => 'Test User',
            'postal_code' => '123-4567', 
            'address' => 'Tokyo, Japan',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee($user->name); 
        $response->assertSee($user->profile_image); 
        $response->assertSee($user->postal_code); 
        $response->assertSee($user->address); 
    }

    /**
     * 未認証時プロフィールページにアクセス
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessProfilePage()
    {
        $response = $this->get('/mypage');

        $response->assertRedirect(route('login'));
    }
}
