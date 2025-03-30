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
     * ユーザーがプロフィールページを開くと必要な情報が表示されるかのテスト
     *
     * @return void
     */
    public function testUserProfileInformationIsDisplayedCorrectly()
    {
        // ダミーユーザーを作成し、ログイン
        $user = User::factory()->create([
            'profile_image' => 'profile_image.jpg', // プロフィール画像のダミー
            'name' => 'Test User', // ユーザー名のダミー
        ]);
        
        // 出品した商品（ダミー）
        $user->products()->create([
            'name' => '商品1',
            'price' => 1000,
            'status' => 'available'
        ]);

        // 購入した商品（ダミー）
        $user->purchases()->create([
            'product_name' => '購入商品1',
            'price' => 500,
            'status' => 'sold'
        ]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // プロフィールページを開く
        $response = $this->get('/profile');

        // 必要な情報が表示されているかを検証
        $response->assertStatus(200);
        $response->assertSee($user->name); // ユーザー名
        $response->assertSee($user->profile_image); // プロフィール画像
        $response->assertSee('商品1'); // 出品した商品
        $response->assertSee('購入商品1'); // 購入した商品
    }

    /**
     * 未認証ユーザーがプロフィールページにアクセスした場合のテスト
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessProfilePage()
    {
        // 未認証ユーザーでプロフィールページにアクセス
        $response = $this->get('/profile');
        
        // 認証されていない場合、ログイン画面にリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }
}
