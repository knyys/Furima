<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test 
     * 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function testNameIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }
    
    /**
     * @test
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function testEmailIsRequired()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * @test
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function testPasswordIsRequired()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     * パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function testPasswordMustBeAtLeast8Characters()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     */
    public function testPasswordAndConfirmationMustMatch()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password098',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     * 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される
     */
    public function testRegistrationSucceedsWhenAllFieldsAreValid()
    {
        Notification::fake();

        // ユーザー登録処理を送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
    
        // 登録後、メール認証画面にリダイレクトされる
        $response->assertRedirect('/email/verify');
    
        // DBにユーザーが作成されている
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    
        // 作成されたユーザーを取得
        $user = \App\Models\User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
    
        // メール送信がされたことを確認
        Notification::assertSentTo(
            User::where('email', 'test@example.com')->first(),
            VerifyEmail::class
        );
    }
}