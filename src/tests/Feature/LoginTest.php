<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function emailIsRequired()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        // 「メールアドレスを入力してください」
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function passwordIsRequired()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        // 「パスワードを入力してください」
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function invalidCredentialsAreRejected()
    {
        $response = $this->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        // 「ログイン情報が登録されていません」
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function validCredentialsLoginSuccessfully()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // ログイン
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // ログイン後、マイページにリダイレクト
        $response->assertRedirect('/mypage');
        $this->assertAuthenticatedAs($user); 
    }
}
