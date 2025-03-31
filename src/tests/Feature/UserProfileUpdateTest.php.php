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
     * ユーザーがプロフィールページを開くと変更項目が初期値として表示されるかのテスト
     *
     * @return void
     */
    public function testUserProfileInformationIsDisplayedWithInitialValues()
    {
        // ダミーユーザーを作成し、ログイン
        $user = User::factory()->create([
            'profile_image' => 'initial_profile_image.jpg', // 初期プロフィール画像
            'name' => 'Test User', // 初期ユーザー名
            'postal_code' => '123-4567', // 初期郵便番号
            'address' => 'Tokyo, Japan', // 初期住所
        ]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // プロフィールページを開く
        $response = $this->get('/mypage');

        // 各項目の初期値が正しく表示されているかを検証
        $response->assertStatus(200);
        $response->assertSee($user->name); // 初期ユーザー名
        $response->assertSee($user->profile_image); // 初期プロフィール画像
        $response->assertSee($user->postal_code); // 初期郵便番号
        $response->assertSee($user->address); // 初期住所
    }

    /**
     * 未認証ユーザーがプロフィールページにアクセスした場合のテスト
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessProfilePage()
    {
        // 未認証ユーザーでプロフィールページにアクセス
        $response = $this->get('/mypage');
        
        // 認証されていない場合、ログイン画面にリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }
}
