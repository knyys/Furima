<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;


class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * 変更項目が初期値として過去設定されていること
     */
    public function testUserProfileInformationIsDisplayedWithInitialValues()
    {
        $user = User::create([
            'name' => '自分',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        // メール認証
        $user->email_verified_at = now();
        $user->save();

        $image = UploadedFile::fake()->create('sample.jpg', 100, 'image/jpeg');
        $imagePath = $image->storeAs('profile', 'sample.jpg', 'public');
        $profile = Profile::create([
            'user_id' => $user->id,
            'address_number' => '000-0000',
            'address' => 'aaaaa',
            'building' => 'bbbbb',
            'image' => 'storage/' . $imagePath,
        ]);

        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee($user->name); 
        $response->assertSee(url('storage/profile/sample.jpg')); 
        $response->assertSee($profile->address_number); 
        $response->assertSee($profile->address); 
        $response->assertSee($profile->building);  
    }

}
