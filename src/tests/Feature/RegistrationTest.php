<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

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
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/email/verify');


        $this->assertTrue(session()->has('register_data'), "セッションに登録データが保存されていません");

        $user = \App\Models\User::where('email', 'test@example.com')->first();
        $this->assertNull($user, "ユーザーがデータベースに保存されています");

        $registerData = session()->get('register_data');

        $verificationUrl = url("/email/verify/{$registerData['email']}/" . sha1($registerData['email']));
        $response = $this->get($verificationUrl);

        $user = \App\Models\User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user, "ユーザーがデータベースに保存されていません");

        $this->assertNotNull($user->email_verified_at, "メール認証が完了していません");

        // メール送信がされたことを確認
        Mail::assertSent(VerifyEmail::class, function ($mail) use ($registerData) {
            return $mail->hasTo($registerData['email']);
        });
    }
}