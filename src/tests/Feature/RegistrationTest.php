<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
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
     */
    public function testRegistrationEmailSent()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // メール送信
        Mail::assertSent(VerifyEmail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });

        $response->assertRedirect(route('verification.notice'));
    }
    
    /**
     * @test
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
     */
    public function testPasswordAndConfirmationMustMatch()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     */
    public function testRegistrationSucceedsWhenAllFieldsAreValid()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}