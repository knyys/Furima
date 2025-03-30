<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testUserCanLogout()
    {
        // ユーザーを作成し、ログインする
        $user = User::factory()->create();

        // ログイン処理
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password', 
        ]);

        // ログイン後、マイページにリダイレクト
        $response->assertRedirect('/mypage');

        // ログアウトボタンを押す
        $response = $this->post('/logout');

        $response->assertRedirect('/login');

        $this->assertGuest();
    }
}