<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test
     * ログアウトができる
     */
    public function testUserCanLogout()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password', 
        ]);

        $response->assertRedirect('/?page=mylist');

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}