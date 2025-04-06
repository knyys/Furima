<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function emailIsRequired()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** 
     * @test
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function passwordIsRequired()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** 
     * @test
     * 入力情報が間違っている場合、バリデーションメッセージが表示される
     */
    public function invalidCredentialsAreRejected()
    {
        // 無効な認証情報でログイン
        $response = $this->post('/login', [
            'email' => 'ng@example.com',  
            'password' => 'password333', 
        ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors('login');  

        $response = $this->get('/login');  
        $response->assertSee('ログイン情報が登録されていません'); 
    }

    /** 
     * @test
     * 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される
     */
    public function validCredentialsLoginSuccessfully()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 実際のリダイレクト先に合わせて修正
        $response->assertRedirect('/?page=mylist');
        $this->assertAuthenticatedAs($user);
    }
}
